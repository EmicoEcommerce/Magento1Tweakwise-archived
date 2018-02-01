<?php
/**
 * @copyright (c) Emico 2014
 */
/** @var Mage_Catalog_Model_Resource_Eav_Mysql4_Setup $installer */
$installer = $this;

$installer->startSetup();
$entityTypeId = $installer->getEntityTypeId('catalog_category');
$attributeSetId = $installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$installer->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'tweakwise_template', [
    'type' => 'int',
    'label' => 'Tweakwise template',
    'input' => 'select',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible' => true,
    'required' => false,
    'source' => 'emico_tweakwise/system_config_source_template',
    'is_html_allowed_on_front' => false,
    'user_defined' => false,
    'default' => null,
]);
$installer->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, 'tweakwise_template', 100);

$installer->endSetup();