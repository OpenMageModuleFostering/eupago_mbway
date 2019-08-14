<?php
class Eupago_Mbway_Model_System_Config_Template
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
			array('value' => 'mbway', 'label' => Mage::helper('adminhtml')->__('Mbway')),
			array('value' => 'nenhum', 'label' => Mage::helper('adminhtml')->__('Nenhum')),
		);
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
			'mbway' 	=> Mage::helper('adminhtml')->__('Mbway'),
			'nenhum' => Mage::helper('adminhtml')->__('Nenhum'),
		);
    }
}