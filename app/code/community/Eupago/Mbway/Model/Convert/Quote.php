<?php
class Eupago_Mbway_Model_Convert_Quote extends Mage_Sales_Model_Convert_Quote
{

    /**
     * Convert quote payment to order payment
     *
     * @param   Mage_Sales_Model_Quote_Payment $payment
     * @return  Mage_Sales_Model_Quote_Payment
     */
    public function paymentToOrderPayment(Mage_Sales_Model_Quote_Payment $payment)
    {
        $orderPayment = parent::paymentToOrderPayment($payment);
        $orderPayment->setEupago_mbw_alias($payment->getEupago_mbw_alias())
            ->setEupago_mbw_referencia($payment->getEupago_mbw_referencia())
            ->setEupago_mbw_montante($payment->getEupago_mbw_montante());
        return $orderPayment;
    }

}
