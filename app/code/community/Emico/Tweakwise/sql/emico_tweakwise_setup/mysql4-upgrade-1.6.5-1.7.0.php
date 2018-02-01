<?php
/**
 * @copyright (c) Emico 2014
 */
/** @var Mage_Catalog_Model_Resource_Eav_Mysql4_Setup $installer */
$installer = $this;

$installer->startSetup();

$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, Emico_Tweakwise_Helper_Data::UPSELL_TEMPLATE_ATTRIBUTE, [
    'type' => 'int',
    'label' => 'Tweakwise upsell template',
    'input' => 'select',
    'source' => 'emico_tweakwise/system_config_source_recommendationProduct',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible' => true,
    'required' => false,
    'user_defined' => false,
    'default' => '',
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => false,
    'unique' => false,
]);

$installer->endSetup();