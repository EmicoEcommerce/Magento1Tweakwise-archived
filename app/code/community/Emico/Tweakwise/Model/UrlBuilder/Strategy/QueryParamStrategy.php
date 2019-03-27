<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */
class Emico_Tweakwise_Model_UrlBuilder_Strategy_QueryParamStrategy implements Emico_Tweakwise_Model_UrlBuilder_Strategy_StrategyInterface
{
    const MULTIVALUE_SEPARATOR = '|';

    /**
     * Builds the URL for a facet attribute
     *
     * @param Emico_Tweakwise_Model_Catalog_Layer $state
     * @param Emico_Tweakwise_Model_Bus_Type_Attribute $attribute
     * @return null|string
     */
    public function buildUrl(Emico_Tweakwise_Model_Catalog_Layer $state, Emico_Tweakwise_Model_Bus_Type_Facet $facet = null, Emico_Tweakwise_Model_Bus_Type_Attribute $attribute = null)
    {
        $query = [
            'ajax' => null,
            'p' => null
        ];
        if ($facet !== null && $attribute !== null) {
            $query = array_merge(
                $query,
                $this->getUrlKeyValPairs($facet, $attribute)
            );
        }

        $params = [
            '_current' => true,
            '_use_rewrite' => true,
            '_escape' => false,
            '_query' => $query
        ];

        return Mage::getUrl('*/*/*', $params);
    }

    /**
     * @param Zend_Controller_Request_Http $request
     * @return Emico_Tweakwise_Model_Bus_Request_Navigation
     */
    public function decorateTweakwiseRequest(Zend_Controller_Request_Http $httpRequest, Emico_Tweakwise_Model_Bus_Request_Navigation $tweakwiseRequest)
    {
        return $tweakwiseRequest;
    }

    /**
     * @param Emico_Tweakwise_Model_Bus_Type_Facet $facet
     * @param Emico_Tweakwise_Model_Bus_Type_Attribute $attribute
     * @return mixed
     */
    protected function getUrlKeyValPairs(Emico_Tweakwise_Model_Bus_Type_Facet $facet, Emico_Tweakwise_Model_Bus_Type_Attribute $attribute)
    {
        $urlKey = $facet->getFacetSettings()->getUrlKey();

        if ($attribute->getIsSelected()) {
            $value = [];
            /** @var $activeAttribute Emico_Tweakwise_Model_Bus_Type_Attribute */
            foreach ($facet->getActiveAttributes() as $activeAttribute) {
                if ($activeAttribute === $attribute) {
                    continue;
                }
                $value[] = $activeAttribute->getTitle();
            }

            $pairs[$urlKey] = $value;
        } elseif ($facet->isMultipleSelect()) {
            $value = $facet->getValue();
            $value[] = $attribute->getTitle();
            $pairs[$urlKey] = $value;
        } else {
            $pairs[$urlKey] = [$attribute->getTitle()];
        }

        return $pairs;
    }

    /**
     * @param Emico_Tweakwise_Model_Catalog_Layer $state
     * @return mixed
     */
    public function buildCanonicalUrl(Emico_Tweakwise_Model_Catalog_Layer $state)
    {
        return $this->buildUrl($state);
    }
}