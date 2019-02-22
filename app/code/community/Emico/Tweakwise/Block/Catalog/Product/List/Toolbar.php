<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Block_Catalog_Product_List_Toolbar
 */
class Emico_Tweakwise_Block_Catalog_Product_List_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar
{
    /**
     * @return Mage_Catalog_Model_Product[]|Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getCollection()
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

    /**
     * @return array|Emico_Tweakwise_Model_Bus_Type_Sortfield[]
     */
    public function getAvailableOrders()
    {
        return $this->getResponseProperties()->getSortFields();
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Properties
     */
    protected function getResponseProperties()
    {
        return $this->getResponse()->getProperties();
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Response_Navigation
     */
    protected function getResponse()
    {
        return $this->getLayer()->getTweakwiseResponse();
    }

    /**
     * Return current URL with rewrites and additional parameters
     *
     * @param array $params Query parameters
     * @return string
     */
    public function getPagerUrl($params = [])
    {
        $urlParams = [];
        $urlParams['_escape'] = true;
        $urlParams['_use_rewrite'] = true;
        $urlParams['_query'] = array_merge(Mage::helper('emico_tweakwise')->getFilteredQuery(), $params);
        return $this->getUrl('*/*/*', $urlParams);
    }
} 