<?php
class Eupago_Mbway_Block_Info extends Mage_Payment_Block_Info
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('mbway/info/info.phtml');
    }
    
    public function getInfo()
    {
        $info = $this->getData('info');
        if (!($info instanceof Mage_Payment_Model_Info)) {
            Mage::throwException($this->__('Can not retrieve payment info model object.'));
        }
        return $info;
    }
    
    public function getMethod()
    {
        return $this->getInfo()->getMethodInstance();
    }
    
    public function toPdf()
    {
        $this->setTemplate('mbway/info/pdf/info.phtml');
        return $this->toHtml();
    }
}