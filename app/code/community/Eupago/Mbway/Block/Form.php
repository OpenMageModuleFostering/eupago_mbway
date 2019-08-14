<?php
class Eupago_Mbway_Block_Form extends Mage_Payment_Block_Form
{
	protected function _construct()
    {
		$mark = Mage::getConfig()->getBlockClassName('core/template');
        $mark = new $mark;
        $mark->setTemplate('mbway/form/mark.phtml');
		
        $this->setTemplate('mbway/form/form.phtml')
			 ->setMethodLabelAfterHtml($mark->toHtml())
		;
		parent::_construct();
    }
}