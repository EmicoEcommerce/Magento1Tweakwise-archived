<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */
class Emico_Tweakwise_Model_UrlBuilder_Strategy_QueryParamStrategy extends Emico_Tweakwise_Model_UrlBuilder_Strategy_AbstractStrategy
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
        $params = ['_current' => true, '_use_rewrite' => true, '_escape' => false];
        $query = ['ajax' => null];

        $query['p'] = null;
        $params['_query'] = $this->getUrlKeyValPairs($facet, $attribute);

        return Mage::getUrl('*/*/*', $params);
    }

    /**
     * @param Zend_Controller_Request_Http $request
     * @return Emico_Tweakwise_Model_Bus_Request_Navigation
     */
    public function decorateTweakwiseRequest(Zend_Controller_Request_Http $httpRequest, Emico_Tweakwise_Model_Bus_Request_Navigation $tweakwiseRequest)
    {
        foreach ($request->getParams() as $key => $value) {
            if (!is_scalar($value) && !is_array($value)) {
                continue;
            }

            if (!$this->applyQueryParam($request, $httpRequest, $key, $value)) {
                $tweakwiseRequest->addFacetKey($key, $value);
            }
        }
    }
}