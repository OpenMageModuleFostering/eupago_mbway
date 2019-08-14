<?php

//http://excellencemagentoblog.com/blog/2012/05/01/magento-create-custom-payment-method-api-based/
   
class Eupago_Mbway_Model_Mbway extends Mage_Payment_Model_Method_Abstract{
    
    protected $_code = 'mbway';
	   
	protected $_paymentMethod = 'eupago_mbway';
     
    protected $_isGateway = true;
 
    protected $_canAuthorize = true;
 
    protected $_canCapture = true;

    protected $_canCapturePartial = false;
 
    protected $_canRefund = false;
 
    protected $_canVoid = false;
 
    protected $_canUseInternal = true;
 
    protected $_canUseCheckout = true;
 
    protected $_canUseForMultishipping  = true;
 
    protected $_canSaveCc = false; // WARNING: you cant keep card data unless you have PCI complience licence
	
	protected $_formBlockType = 'mbway/form_mbway';
	
    protected $_infoBlockType = 'mbway/info_mbway';
	  
	public function order(Varien_Object $payment, $amount)
    {
        $order = $payment->getOrder();
		$alias = $payment->getAdditionalInformation('mbway_phone_number');
		
        $result = $this->soapApiPedidoMBW($payment,$amount,$alias);

        if($result == false) {
            $errorMsg = $this->_getHelper()->__('Error Processing the request');
        } else {
            if($result->estado == 0){
                $payment->setTransactionId($result->referencia);
				$payment->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS,array('referencia'=>$result->referencia,'resposta'=>$result->resposta,'method'=>'MBWAY','alias'=>$result->alias)); 
				$payment->setIsTransactionClosed(false);
				$payment->setAdditionalInformation('pedido', $result->referencia);
				$payment->setAdditionalInformation('valor', $result->valor);
		   }else{
			    $payment->setTransactionId(-1);
				$payment->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS,array('error_cod'=>$result->estado,'error_description'=>$result->resposta));
				$payment->setIsTransactionClosed(false);
			    $errorMsg = $result->resposta;  
            }
        }
				
        if(isset($errorMsg)){
			Mage::log("pedido com erro: ".$errorMsg, null, 'eupago_mbway.log');
            Mage::getSingleton('core/session')->addError($errorMsg);
			Mage::throwException($errorMsg);
        }

        return $this;
    }
	
	public function capture(Varien_Object $payment)
	{
		if($payment->getMethod() != 'mbway')
			return;
		
		// vai á base de dados buscar todas as transaction desta encomenda
		$collection = Mage::getModel('sales/order_payment_transaction')
                  ->getCollection()
                  ->addAttributeToFilter('order_id', array('eq' => $payment->getOrder()->getEntityId()))
                  ->addAttributeToFilter('txn_type', array('eq' => 'order'))
                  ->addPaymentIdFilter($payment->getId());

		foreach($collection as $transaction){
			   $referencia = is_numeric($transaction->getTxnId()) ? $transaction->getTxnId() : null ;
		}
		
		if(!(isset($referencia) && $referencia != null)){
			Mage::throwException("Não foi encontrado pedido Mbway");
		}	

		$result = $this->soapApiInformacaoReferencia($referencia);
 
		if($result == false) {
            $errorMsg = $this->_getHelper()->__('Error Processing the request');
        } else {
            if($result->estado_referencia == 'paga' || $result->estado_referencia == 'transferida'){
				// neste sistema altera logo para pago
				$payment->setTransactionId($referencia."-capture");
				$payment->setParentTransactionId($referencia);
                $payment->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS,array('referencia'=>$referencia,'resposta'=>$result->resposta,'method'=>'MBWAY', "data de pagamento"=>$result->data_pagamento,  "hora de pagamento"=>$result->hora_pagamento));
				$payment->setIsTransactionClosed(true);
		    }else{
                $errorMsg = "a referencia não se encontra paga";
            }
        }
		
        if(isset($errorMsg)){
            Mage::throwException($errorMsg);
        }
		
        return $this;
	}


	private function getSoapUrl(){
		$version = 'eupagov5';
		$chave = $this->getConfigData('chave');
		$demo = explode("-",$chave);

		if($demo[0] == 'demo'){
			return 'https://replica.eupago.pt/replica.'.$version.'.wsdl';
		}
		return 'https://seguro.eupago.pt/'.$version.'.wsdl';
	}
	
	
	// faz pedido à eupago via SOAP Pagamento
	private function soapApiPedidoMBW(Varien_Object $payment, $amount, $alias){
		
		$order = $payment->getOrder();
			
		$arraydados = array("chave" => $this->getConfigData('chave'), "valor" => $amount, "id" => $order->getIncrementId(), "alias"=>$alias);
		
		$client = new SoapClient($this->getSoapUrl(), array('cache_wsdl' => WSDL_CACHE_NONE));// chamada do serviço SOAP

		try {
			$result = $client->pedidoMBW($arraydados);
		}
		catch (SoapFault $fault) {
			Mage::throwException("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring}");
			return false;
		}
				
		return $result;
	}
	 
	// faz pedido à eupago para obter o estado da referencia
	private function soapApiInformacaoReferencia($referencia){
		
		$arraydados = array("chave" => $this->getConfigData('chave'), "referencia" => $referencia, "entidade" => "00000");
		
		$client = new SoapClient($this->getSoapUrl(), array('cache_wsdl' => WSDL_CACHE_NONE));// chamada do serviço SOAP

		try {
			$result = $client->informacaoReferencia($arraydados);
		}
		catch (SoapFault $fault) {
			Mage::throwException("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring}");
			return false;
		}
				
		return $result;
	}
	
		
	public function assignData($data){
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->getInfoInstance();
		$info->setAdditionalInformation('mbway_phone_number', $data->getMbwayPhoneNumber());
        //$info->setMbwayPhoneNumber($data->getMbwayPhoneNumber());

        return $this;
    }
 
 
    public function validate(){
        parent::validate();
 
        $info = $this->getInfoInstance();
        $no = $info->getAdditionalInformation('mbway_phone_number');
		
        if(empty($no) || !is_numeric($no) || strlen ($no) != 9){
            $errorCode = 'invalid_data';
            $errorMsg = $this->_getHelper()->__('O campo Número Mbway parece ser inválido. Por favor verifique');
        }
 
        if(isset($errorMsg)){
            Mage::throwException($errorMsg);
        }
		
        return $this;
    }
 
 }