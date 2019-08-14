<?php
class Eupago_Mbway_Block_Form_Mbway extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
		if(Mage::getStoreConfig('payment/mbway/frontend_template') == 'mbway')
			$this->setTemplate('eupago/mbway/form/mbway.phtml');
		else
			$this->setTemplate('eupago/mbway/form/default.phtml');
		
		if(Mage::getStoreConfig('payment/mbway/mostra_icon'))
			$this->setMethodLabelAfterHtml('<img style="padding:0 5px;"src="'.$this->getSkinUrl('images/eupago/mbway/mbway_icon.png').'" />');
    }
}