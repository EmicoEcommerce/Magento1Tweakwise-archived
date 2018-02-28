<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */
class Emico_Tweakwise_Model_UrlBuilder_Strategy_CategoryStrategy implements Emico_Tweakwise_Model_UrlBuilder_Strategy_StrategyInterface
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
        if (!$facet->isCategory()) {
            return null;
        }

        $helper = Mage::helper('emico_tweakwise');
        $query = Mage::app()->getRequest()->getQuery();
        $category = $helper->getFilterCategory($attribute->getAttributeId());
        $query['p'] = null;
        $query['ajax'] = null;

        if ($helper->categoryAsLink()) {
            $url = $category->getUrl();
        } else {
            $attributeParams = [];
            parse_str(parse_url($attribute->getUrl(), PHP_URL_QUERY), $attributeParams);
            if (isset($attributeParams['tn_cid'])) {
                $categoryIds = explode('-', $attributeParams['tn_cid']);
                if ($attribute->getIsSelected()) {
                    array_pop($categoryIds);
                }
            } else {
                $categoryIds = [];
            }

            $currentCategory = $state->getCurrentCategory();
            $categories = [];
            foreach ($categoryIds as $categoryId) {
                $category = $helper->getFilterCategory($categoryId);
                if (!$currentCategory || $currentCategory->getId() != $category->getId()) {
                    $categories[] = $category->getUrlKey();
                }
            }

            $query['cat'] = implode('|', $categories);
            if (Mage::registry('current_category')) {
                $url = Mage::registry('current_category')->getUrl();
            } else {
                $params['_query'] = $query;

                return Mage::getUrl('*/*/*', $params);
            }
        }

        $query = array_filter($query);
        if ($query) {
            $url .= '?' . http_build_query($query);
        }

        return $url;
    }
}