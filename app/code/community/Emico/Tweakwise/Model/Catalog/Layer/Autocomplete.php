<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Model_Catalog_Layer_Autocomplete
 */
class Emico_Tweakwise_Model_Catalog_Layer_Autocomplete extends Emico_Tweakwise_Model_Catalog_Layer
{
    /**
     * Query variable name
     */
    const QUERY_VAR_NAME = 'categoryid';

    /**
     * @return Emico_Tweakwise_Model_Bus_Request_Autocomplete
     */
    public function getTweakwiseRequest()
    {
        return parent::getTweakwiseRequest();
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Response_Autocomplete
     */
    public function getTweakwiseResponse()
    {
        return parent::getTweakwiseResponse();
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Request_Autocomplete
     */
    protected function createTweakwiseRequest()
    {
        $request = Mage::getModel('emico_tweakwise/bus_request_autocomplete');

        $request
            ->setQuery(Mage::helper('catalogsearch')->getQueryText())
            ->setGetProducts($this->getConfig('products'))
            ->setGetSuggestions($this->getConfig('suggestions'))
            ->setMaxResult($this->getConfig('max_result'));

        if ($categoryId = Mage::app()->getRequest()->getParam(self::QUERY_VAR_NAME)) {
            $request->setCategory($categoryId);
        }

        return $request;
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