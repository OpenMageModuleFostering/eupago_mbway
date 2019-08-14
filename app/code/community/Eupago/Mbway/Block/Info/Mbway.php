<?php
class Eupago_Mbway_Block_Info_Mbway extends Mage_Payment_Block_Info
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('eupago/mbway/info/mbway.phtml');
    }
    
    public function getInfo()
    {
        $info = $this->getData('info');
        if (!($info instanceof Mage_Payment_Model_Info)) {
            Mage::throwException($this->__('Can not retrieve payment info model object.'));
        }
        return $info;
    }
	
	public function getMbwayData(){
		$info = $this->getData('info');
		$mbway_data = (Object)$info['additional_information'];
        if (!($info instanceof Mage_Payment_Model_Info)) {
            Mage::throwException($this->__('Can not retrieve payment info model object.'));
        }
        return $mbway_data;
	}
    
    public function getMethod()
    {
        return $this->getInfo()->getMethodInstance();
    }
}