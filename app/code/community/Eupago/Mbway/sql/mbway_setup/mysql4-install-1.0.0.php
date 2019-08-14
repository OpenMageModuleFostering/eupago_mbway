<?php
$installer = $this;

$installer->startSetup();
$installer->addAttribute('order_payment', 'eupago_mbw_alias', array('type'=>'varchar'));
$installer->addAttribute('order_payment', 'eupago_mbw_referencia', array('type'=>'varchar'));
$installer->addAttribute('order_payment', 'eupago_mbw_montante', array('type'=>'varchar'));

$installer->addAttribute('quote_payment', 'eupago_mbw_alias', array('type'=>'varchar'));
$installer->addAttribute('quote_payment', 'eupago_mbw_referencia', array('type'=>'varchar'));
$installer->addAttribute('quote_payment', 'eupago_mbw_montante', array('type'=>'varchar'));
$installer->endSetup();

if (Mage::getVersion() >= 1.1) {
    $installer->startSetup();    
	$installer->getConnection()->addColumn($installer->getTable('sales_flat_quote_payment'), 'eupago_mbw_alias', 'VARCHAR(255) NOT NULL');
	$installer->getConnection()->addColumn($installer->getTable('sales_flat_quote_payment'), 'eupago_mbw_referencia', 'VARCHAR(255) NOT NULL');
	$installer->getConnection()->addColumn($installer->getTable('sales_flat_quote_payment'), 'eupago_mbw_montante', 'VARCHAR(255) NOT NULL');
    $installer->endSetup();
}