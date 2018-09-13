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
        // Certain filter combinations are allowed for indexing
        if ($this->isInCombinationWhitelist($facetLinkedTo)) {
            return false;
        }

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
        $layer = Mage::getSingleton('emico_tweakwise/catalog_layer');
        $noFollowFacets = explode(',', Mage::getStoreConfig('emico_tweakwise/navigation/nofollow_facets'));
        $selectedFacets = $layer->getSelectedFacets();
        if ($facetLinkedTo !== null) {
            $selectedFacets[] = $facetLinkedTo;
        }

        foreach ($selectedFacets as $facet) {
            $attributeCode = $facet->getFacetSettings()->getAttributeName();
            $source = $facet->getFacetSettings()->getSource();

            if ($source === Emico_Tweakwise_Model_Bus_Type_Facet_Settings::FACET_SOURCE_DERIVATIVE) {
                return true;
            }

            if (in_array($attributeCode, $noFollowFacets, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the combination of 2 filters is allowed for indexing
     *
     * @return bool
     */
    protected function isInCombinationWhitelist(Emico_Tweakwise_Model_Bus_Type_Facet $facetLinkedTo = null)
    {
        $layer = Mage::getSingleton('emico_tweakwise/catalog_layer');
        $combinationWhitelist = Mage::getStoreConfig('emico_tweakwise/navigation/filter_combination_indexable');
        if (empty($combinationWhitelist)) {
            return false;
        }

        $facets = [];
        if ($facetLinkedTo !== null) {
            $facets[] = $facetLinkedTo;
        }

        $facets = array_merge($facets, $layer->getSelectedFacets());

        // Only check filter combination when the URL contains exactly two filters
        if (\count($facets) !== 2) {
            return false;
        }

        $combinationWhitelist = unserialize($combinationWhitelist);

        $facetsCodes = array_unique(array_map(function(Emico_Tweakwise_Model_Bus_Type_Facet $facet) {
            return $facet->getFacetSettings()->getCode();
        }, $facets));

        foreach ($combinationWhitelist as $filterCombination) {
            if (in_array($filterCombination['filter1'], $facetsCodes) && in_array($filterCombination['filter2'], $facetsCodes)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function shouldAddCanonicalTag()
    {
        return Mage::getStoreConfig('emico_tweakwise/navigation/add_canonical_tag');
    }
}