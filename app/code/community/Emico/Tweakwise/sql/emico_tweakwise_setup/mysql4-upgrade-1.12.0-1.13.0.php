<?php
/**
 * @author : Bram Gerritsen, email: bgerritsen@emico.nl.
 * @copyright : Copyright Emico B.V. 2018.
 */
/** @var Mage_Catalog_Model_Resource_Eav_Mysql4_Setup $installer */
$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('emico_tweakwise/slug_attribute_mapping'))
    ->addColumn(
        'mapping_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary'  => true,
        ), 'Unique identifier'
    )
    ->addColumn(
        'slug', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'URL slug'
    )
    ->addColumn(
        'attribute_code', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'Attribute code in tweakwise'
    )->addColumn(
        'attribute_value', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'Attribute value in tweakwise'
    );

if (!$installer->getConnection()->isTableExists($table->getName())) {
    $installer->getConnection()->createTable($table);
}

$installer->endSetup();