<?php
/** @var Mage_Core_Model_Resource_Setup $installer */

$installer = $this;

$installer->startSetup();

try {
    /** @var Mage_Eav_Model_Attribute $attribute */
    $attribute = Mage::getModel('eav/entity_attribute');
    $attribute->loadByCode(Mage_Catalog_Model_Category::ENTITY, 'tweakwise_upsell_template');

    $attribute->setData('source_model', 'emico_tweakwise/system_config_source_recommendationProduct');
    $attribute->save();
} catch (Exception $e) {
    die('Unable to update source_model for the attribute');
}

$installer->endSetup();
