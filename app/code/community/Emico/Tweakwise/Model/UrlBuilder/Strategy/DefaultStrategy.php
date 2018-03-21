<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */
class Emico_Tweakwise_Model_UrlBuilder_Strategy_PathStrategy extends Emico_Tweakwise_Model_UrlBuilder_Strategy_AbstractStrategy implements
    Emico_Tweakwise_Model_UrlBuilder_Strategy_StrategyInterface,
    Emico_Tweakwise_Model_UrlBuilder_Strategy_RoutingStrategyInterface
{
    /**
     * Builds the URL for a facet attribute
     *
     * @param Emico_Tweakwise_Model_Catalog_Layer $state
     * @param Emico_Tweakwise_Model_Bus_Type_Attribute $attribute
     * @return null|string
     */
    public function buildUrl(Emico_Tweakwise_Model_Catalog_Layer $state, Emico_Tweakwise_Model_Bus_Type_Facet $facet, Emico_Tweakwise_Model_Bus_Type_Attribute $attribute)
    {

    }

    /**
     * @param Zend_Controller_Request_Http $httpRequest
     * @param Emico_Tweakwise_Model_Bus_Request_Navigation $tweakwiseRequest
     * @return Emico_Tweakwise_Model_Bus_Request_Navigation
     * @internal param Zend_Controller_Request_Http $request
     */
    public function decorateTweakwiseRequest(Zend_Controller_Request_Http $httpRequest, Emico_Tweakwise_Model_Bus_Request_Navigation $tweakwiseRequest)
    {
        return $this->getSelectedStrategy()->decorateTweakwiseRequest($httpRequest, $tweakwiseRequest);
    }

    /**
     * If you need to do custom routing implement this method
     *
     * @param Zend_Controller_Request_Http $request
     * @return bool
     */
    public function matchUrl(Zend_Controller_Request_Http $request)
    {
        if ($this->getSelectedStrategy() instanceof Emico_Tweakwise_Model_UrlBuilder_Strategy_RoutingStrategyInterface) {
            return $this->getSelectedStrategy()->matchUrl($request);
        }
        return false;
    }

    /**
     * @return Emico_Tweakwise_Model_UrlBuilder_Strategy_StrategyInterface|Emico_Tweakwise_Model_UrlBuilder_Strategy_RoutingStrategyInterface
     */
    protected function getSelectedStrategy()
    {

    }
}