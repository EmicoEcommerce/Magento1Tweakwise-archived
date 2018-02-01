<?php
/**
 * @author : Edwin Jacobs, email: ejacobs@emico.nl.
 * @copyright : Copyright Emico B.V. 2017.
 */
/** @var Mage_Catalog_Model_Resource_Eav_Mysql4_Setup $installer */
$installer = $this;
$installer->startSetup();

$setId = Mage::getSingleton('eav/config')->getEntityType('catalog_category')->getDefaultAttributeSetId();
$categoryEntityType = Mage_Catalog_Model_Category::ENTITY;

$tweakwiseGroupId = 'Tweakwise';
//Add Tweakwise Attribute group
$installer->addAttributeGroup(
    'catalog_category',
    $setId,
    $tweakwiseGroupId
);

$installer->addAttribute($categoryEntityType, Emico_Tweakwise_Helper_Data::UPSELL_TEMPLATE_ATTRIBUTE, [
    'type' => 'int',
    'label' => 'Tweakwise upsell template',
    'input' => 'select',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible' => true,
    'required' => false,
    'source' => 'emico_tweakwise/system_config_source_recommendationFeatured',
    'is_html_allowed_on_front' => false,
    'user_defined' => false,
    'default' => null,
]);

$installer->addAttribute($categoryEntityType, Emico_Tweakwise_Helper_Data::RELATED_TEMPLATE_ATTRIBUTE, [
    'type' => 'int',
    'label' => 'Tweakwise cross sell template',
    'input' => 'select',
    'source' => 'emico_tweakwise/system_config_source_recommendationProduct',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible' => true,
    'required' => false,
    'user_defined' => false,
    'default' => null,
]);

// Move existing tweakwise category attributes to new Tweakwise group
$installer->addAttributeToGroup(
    $categoryEntityType,
    $setId,
    $tweakwiseGroupId,
    'tweakwise_template'
);
$installer->addAttributeToGroup(
    $categoryEntityType,
    $setId,
    $tweakwiseGroupId,
    'tweakwise_featured_template'
);

$installer->addAttributeToGroup(
    $categoryEntityType,
    $setId,
    $tweakwiseGroupId,
    Emico_Tweakwise_Helper_Data::UPSELL_TEMPLATE_ATTRIBUTE
);

$installer->addAttributeToGroup(
    $categoryEntityType,
    $setId,
    $tweakwiseGroupId,
    Emico_Tweakwise_Helper_Data::RELATED_TEMPLATE_ATTRIBUTE
);




$installer->endSetup();