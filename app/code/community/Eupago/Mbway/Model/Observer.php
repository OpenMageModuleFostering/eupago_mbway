<?php

class Eupago_Mbway_Model_Observer
{
    public function pendingPaymentState($observer)
    {
		$order = $observer->getOrder();
		$method = $order->getPayment()->getMethodInstance();
		if ($method->getCode() == 'mbway')
			$order->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT, true, 'A aguardar pagamento por mbway');
    }
	
	public function sendInvoiceEmail($observer)
	{
		$invoice = $observer->getEvent()->getInvoice();
		$order = $invoice->getOrder();
		$method = $order->getPayment()->getMethodInstance();
		$sendEmail = Mage::getStoreConfig('payment/mbway/send_invoice_email');
		if ($method->getCode() == 'mbway' && $sendEmail){
			$invoice->sendEmail();
		}
	}
}