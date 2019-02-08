<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Helper class for Emico_Tweakwise
 */
class Emico_Tweakwise_Helper_Seo extends Mage_Core_Helper_Abstract
{
    /**
     * Check if we need to apply a noindex nofollow
     *
     *
     * @param Emico_Tweakwise_Model_Bus_Type_Facet|null $facetLinkedTo
     * @return bool
     */
    public function shouldApplyNoIndexNoFollow(Emico_Tweakwise_Model_Bus_Type_Facet $facetLinkedTo = null)
    {
        if ($this->exceedsAttributeLimit($facetLinkedTo)) {
            return true;
        }

        if ($this->isInFacetBlacklist($facetLinkedTo)) {
            return true;
        }

        return false;
    }

    /**
     * Check if the number of selected facets doesn't exceed the max facets to be indexable
     *
     * @param Emico_Tweakwise_Model_Bus_Type_Facet|null $facetLinkedTo
     * @return bool
     */
    protected function exceedsAttributeLimit(Emico_Tweakwise_Model_Bus_Type_Facet $facetLinkedTo = null)
    {
        $layer = Mage::getSingleton('emico_tweakwise/catalog_layer');
        $maxFacetsForNoFollow = Mage::getStoreConfig('emico_tweakwise/navigation/nofollow_max_facets');

        if (empty($maxFacetsForNoFollow)) {
            return false;
        }

        $selectedAttributes = $layer->getSelectedAttributes();
        $selectedAttributesCount = count($selectedAttributes);
        if ($facetLinkedTo !== null) {
            $selectedAttributesCount++;
        }

        return ($selectedAttributesCount > $maxFacetsForNoFollow);
    }

    /**
     * Check if the given facet is not in the blacklist for indexing by robots
     *
     * @return bool
     */
    protected function isInFacetBlacklist(Emico_Tweakwise_Model_Bus_Type_Facet $facetLinkedTo = null)
    {
        $noFollowFacets = array_filter(explode(',', Mage::getStoreConfig('emico_tweakwise/navigation/nofollow_facets')));

        if ($facetLinkedTo !== null && in_array($facetLinkedTo->getFacetSettings()->getUrlKey(), $noFollowFacets)) {
            return true;
        }
        $layer = Mage::getSingleton('emico_tweakwise/catalog_layer');

        foreach ($layer->getFacets() as $facet) {
            if (!in_array($facet->getFacetSettings()->getUrlKey(), $noFollowFacets)) {
                continue;
            }
            if ($facet->hasActiveAttributes()) {
                return true;
            }
        }

        return false;
    }
}