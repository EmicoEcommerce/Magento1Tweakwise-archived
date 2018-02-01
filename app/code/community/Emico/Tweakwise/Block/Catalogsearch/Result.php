<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Block_Catalogsearch_Result
 *
 * @method string|null getHeaderText();
 */
class Emico_Tweakwise_Block_Catalogsearch_Result extends Mage_CatalogSearch_Block_Result
{
    /**
     * @return int
     */
    public function getResultCount()
    {
        return Mage::getSingleton('emico_tweakwise/catalog_layer')->getProductCount();
    }

    /**
     * {@inheritDoc}
     */
    protected function _beforeToHtml()
    {
        Mage::dispatchEvent('catalog_block_product_list_collection', [
            'collection' => Mage::getSingleton('emico_tweakwise/catalog_layer')->getProducts(),
        ]);
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if (!Mage::helper('emico_tweakwise')->isEnabled('search')) {
            return '';
        }

        return parent::_toHtml();
    }
}
