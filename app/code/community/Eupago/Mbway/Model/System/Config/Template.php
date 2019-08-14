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
            array('value' => 'nenhum', 'label' => Mage::helper('adminhtml')->__('Nenhum')),
			array('value' => 'mbway', 'label' => Mage::helper('adminhtml')->__('Mbway')),
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
            'nenhum' => Mage::helper('adminhtml')->__('Nenhum'),
			'mbway' 	=> Mage::helper('adminhtml')->__('Mbway'),
		);
    }
}