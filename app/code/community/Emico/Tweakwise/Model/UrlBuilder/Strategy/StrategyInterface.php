<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */
interface Emico_Tweakwise_Model_UrlBuilder_Strategy_StrategyInterface
{
    /**
     * Builds the URL for a facet attribute
     *
     * @param Emico_Tweakwise_Model_Catalog_Layer $state
     * @param Emico_Tweakwise_Model_Bus_Type_Attribute $attribute
     * @return null|string
     */
    public function buildUrl(Emico_Tweakwise_Model_Catalog_Layer $state, Emico_Tweakwise_Model_Bus_Type_Facet $facet, Emico_Tweakwise_Model_Bus_Type_Attribute $attribute);

    /**
     * @param Zend_Controller_Request_Http $request
     * @return Emico_Tweakwise_Model_Bus_Request_Navigation
     */
    public function decorateTweakwiseRequest(Zend_Controller_Request_Http $httpRequest, Emico_Tweakwise_Model_Bus_Request_Navigation $tweakwiseRequest);
}