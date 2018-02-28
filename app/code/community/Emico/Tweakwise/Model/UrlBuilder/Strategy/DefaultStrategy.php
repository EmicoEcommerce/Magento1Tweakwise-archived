<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */
class Emico_Tweakwise_Model_UrlBuilder_Strategy_DefaultStrategy implements Emico_Tweakwise_Model_UrlBuilder_Strategy_StrategyInterface
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

            $query[$urlKey] = count($value) > 0 ? implode('|', $value) : null;
        } elseif ($facet->isMultipleSelect()) {
            $value = $facet->getValue();
            $value[] = $attribute->getTitle();
            $query[$urlKey] = implode('|', $value);
        } else {
            $query[$urlKey] = $attribute->getTitle();
        }

        $query['p'] = null;
        $params['_query'] = $query;

        return Mage::getUrl('*/*/*', $params);
    }
}