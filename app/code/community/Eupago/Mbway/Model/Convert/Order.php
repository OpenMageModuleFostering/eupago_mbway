<?php
class Eupago_Mbway_Model_Convert_Order extends Mage_Sales_Model_Convert_Order
{
    /**
     * Convert order payment to quote payment
     *
     * @param   Mage_Sales_Model_Order_Payment $payment
     * @return  Mage_Sales_Model_Quote_Payment
     */
    public function paymentToQuotePayment(Mage_Sales_Model_Order_Payment $payment, $quotePayment=null)
    {
        $quotePayment = parent::paymentToQuotePayment($payment, $quotePayment);

		var_dump($quotePayment);
		
        $quotePayment->setEupago_mbw_alias($payment->getEupago_mbw_alias())
						->setEupago_mbw_referencia($payment->getEupago_mbw_referencia())
						->setEupago_mbw_montante($payment->getEupago_mbw_montante());

        return $quotePayment;
    }
	
	
}
