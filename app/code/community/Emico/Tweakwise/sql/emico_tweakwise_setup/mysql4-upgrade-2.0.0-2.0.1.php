<?php
/**
 * @author : Edwin Jacobs, email: ejacobs@emico.nl.
 * @copyright : Copyright Emico B.V. 2020.
 */
/** @var Mage_Catalog_Model_Resource_Eav_Mysql4_Setup $installer */
$installer = $this;
$installer->startSetup();

Mage::getConfig()->saveConfig(
    'emico_tweakwise/global/server_url',
    'https://gateway.tweakwisenavigator.com/',
    'default',
    0
);

$installer->endSetup();
