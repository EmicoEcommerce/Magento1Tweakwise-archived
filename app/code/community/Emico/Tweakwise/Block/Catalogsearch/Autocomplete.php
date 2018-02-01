<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Block_CatalogSearch_Autocomplete
 */
class Emico_Tweakwise_Block_CatalogSearch_Autocomplete extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * @var Mage_Catalog_Model_Resource_Product_Collection|Mage_Catalog_Model_Product[]
     */
    protected $_products;

    /**
     * {@inheritDoc}
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('emico_tweakwise/catalogsearch/autocomplete/result.phtml');
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Suggestion[]
     */
    public function getSuggestions()
    {
        return $this->getLayer()->getTweakwiseResponse()->getSuggestions();
    }

    /**
     * @return Emico_Tweakwise_Model_Layer_Autocomplete
     */
    public function getLayer()
    {
        return Mage::getSingleton('emico_tweakwise/catalog_layer_autocomplete');
    }

    /**
     * @return string
     */
    public function _toHtml()
    {
        if ($this->getCatalogSearchHelper()->getQueryText() == '') {
            return '';
        }

        return parent::_toHtml();
    }

    /**
     * @return Mage_CatalogSearch_Helper_Data
     */
    public function getCatalogSearchHelper()
    {
        return $this->helper('catalogsearch');
    }

    /**
     * @return Mage_Catalog_Model_Resource_Product_Collection|Mage_Catalog_Model_Product[]
     */
    public function getProducts()
    {
        return $this->getLayer()->getProducts();
    }

    /**
     * @param string $config
     * @return bool
     */
    protected function getConfig($config)
    {
        return Mage::getStoreConfig('emico_tweakwise/autocomplete/' . $config);
    }
}
