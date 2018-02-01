<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Block_Catalog_Product_List
 */
class Emico_Tweakwise_Block_Catalog_Product_List extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * Retrieve current view mode
     *
     * @return string
     */
    public function getMode()
    {
        return $this->getChild('tweakwise.toolbar')->getCurrentMode();
    }

    /**
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('tweakwise.toolbar');
    }

    /**
     * {@inheritDoc}
     */
    public function _toHtml()
    {
        return Mage::helper('emico_tweakwise')->isEnabled('navigation') ? parent::_toHtml() : '';
    }

    /**
     * {@inheritDoc}
     */
    protected function _beforeToHtml()
    {
        Mage::dispatchEvent('catalog_block_product_list_collection', [
            'collection' => $this->getLoadedProductCollection(),
        ]);
    }

    /**
     * @return Mage_Catalog_Model_Product[]|Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getLoadedProductCollection()
    {
        return $this->getLayer()->getProducts();
    }

    /**
     * @return Emico_Tweakwise_Model_Catalog_Layer
     */
    protected function getLayer()
    {
        return Mage::getSingleton('emico_tweakwise/catalog_layer');
    }
}