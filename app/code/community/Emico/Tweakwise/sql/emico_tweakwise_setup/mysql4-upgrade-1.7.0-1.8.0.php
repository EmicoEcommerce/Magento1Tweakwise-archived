<?php
/**
 * @copyright (c) Emico 2014
 */
/** @var Mage_Catalog_Model_Resource_Eav_Mysql4_Setup $installer */
$installer = $this;

$installer->startSetup();

$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, Emico_Tweakwise_Helper_Data::RELATED_TEMPLATE_ATTRIBUTE, [
    'type' => 'int',
    'label' => 'Tweakwise cross sell template',
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

$entityTypeId = $installer->getEntityTypeId('catalog_category');
$attributeSetId = $installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$installer->addAttribute(Mage_Catalog_Model_Category::ENTITY, Emico_Tweakwise_Helper_Data::FEATURED_TEMPLATE_ATTRIBUTE, [
    'type' => 'int',
    'label' => 'Tweakwise featured product template',
    'input' => 'select',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible' => true,
    'required' => false,
    'source' => 'emico_tweakwise/system_config_source_recommendationFeatured',
    'is_html_allowed_on_front' => false,
    'user_defined' => false,
    'default' => null,
]);
$installer->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, Emico_Tweakwise_Helper_Data::FEATURED_TEMPLATE_ATTRIBUTE, 101);

$installer->endSetup();