<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */
abstract class Emico_Tweakwise_Model_UrlBuilder_Strategy_AbstractStrategy implements Emico_Tweakwise_Model_UrlBuilder_Strategy_StrategyInterface
{
    const MULTIVALUE_SEPARATOR = '|';

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
}