<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */
class Emico_Tweakwise_Model_UrlBuilder_UrlBuilder
{
    /**
     * @param Emico_Tweakwise_Model_Catalog_Layer $state
     * @param Emico_Tweakwise_Model_Bus_Type_Attribute $attribute
     * @return null|string
     * @throws Exception
     */
    public function buildUrl(Emico_Tweakwise_Model_Bus_Type_Facet $facet = null, Emico_Tweakwise_Model_Bus_Type_Attribute $attribute = null)
    {
        $helper = Mage::helper('emico_tweakwise/uriStrategy');

        $state = Mage::getSingleton('emico_tweakwise/catalog_layer');
        foreach ($helper->getActiveStrategies() as $strategy) {
            $url = $strategy->buildUrl($state, $facet, $attribute);
            if ($url !== null) {
                $url = new Varien_Object(['url' => $url]);
                Mage::dispatchEvent('tweakwise_urlbuilder_buildurl', ['strategy' => $strategy, 'url' => $url]);
                return $url->getData('url');
            }
        }
        throw new \Exception('No strategy was able to generate a URL');
    }

    /**
     * Get URL to clear all filters
     */
    public function getClearUrl()
    {
        $currentCategory = Mage::registry('current_category');
        if ($currentCategory instanceof Mage_Catalog_Model_Category) {
            return $currentCategory->getUrl() . '#no-ajax';
        }
        
        $state = Mage::getSingleton('emico_tweakwise/catalog_layer');
        $filterState = [];

        $facetsBlocks = $state->getSelectedFacets();
        foreach ($facetsBlocks as $facetBlock) /** @var $_facetBlock Emico_Tweakwise_Block_Catalog_Layer_Facet_Attribute */ {
            $filterState[$facetBlock->getUrlKey()] = $facetBlock->getCleanValue();
        }

        $params['_current'] = true;
        $params['_use_rewrite'] = true;
        $params['_query'] = $filterState;
        $params['_escape'] = true;

        return Mage::getUrl('*/*/*', $params) . '#no-ajax';
    }

    public function buildCanonicalUrl()
    {
        /** @var Emico_Tweakwise_Helper_UriStrategy $helper */
        $helper = Mage::helper('emico_tweakwise/uriStrategy');
        /** @var Emico_Tweakwise_Model_Catalog_Layer $state */
        $state = Mage::getSingleton('emico_tweakwise/catalog_layer');
        foreach ($helper->getActiveStrategies() as $strategy) {
            $url = $strategy->buildCanonicalUrl($state);
            if ($url !== null) {
                $url = new Varien_Object(['url' => $url]);
                Mage::dispatchEvent('tweakwise_urlbuilder_buildurl', ['strategy' => $strategy, 'url' => $url]);
                return $url->getData('url');
            }
        }
        throw new \Exception('No strategy was able to generate a URL');
    }
}