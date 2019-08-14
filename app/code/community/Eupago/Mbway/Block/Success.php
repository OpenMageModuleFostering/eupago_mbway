<?php
class Eupago_Mbway_Block_Success extends Mage_Checkout_Block_Onepage_Success
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('/mbway/checkout/success.phtml');
    }

}

?>