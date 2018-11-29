<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Filter state view block. Overwrites default template.
 */
class Emico_Tweakwise_Block_Catalog_Layer_State extends Emico_Tweakwise_Block_Catalog_Layer_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('emico_tweakwise/catalog/layer/state.phtml');
    }

    /**
     * @param Emico_Tweakwise_Block_Catalog_Layer_Facets $facets
     */
    public function setFacetsBlock(Emico_Tweakwise_Block_Catalog_Layer_Facets $facets)
    {
        $this->_facets = $facets;
    }

    /**
     * Retrieve Clear Filters URL
     *
     * @return string
     */
    public function getClearUrl()
    {
        /** @var Emico_Tweakwise_Model_UrlBuilder_UrlBuilder $urlBuilder */
        $urlBuilder = Mage::getModel('emico_tweakwise/urlBuilder_urlBuilder');
        return $urlBuilder->getClearUrl();
    }

    /**
     * @return Emico_Tweakwise_Block_Catalog_Layer_Facet_Attribute[]
     */
    public function getActiveFacets()
    {
        $facetBlock = $this->getParentBlock()->getChild('facets');
        if (!$facetBlock instanceof Emico_Tweakwise_block_Catalog_Layer_Facets) {
            return [];
        }

        $result = [];
        foreach ($facetBlock->getActive() as $facetBlock) {
            if (!$this->showFacetState($facetBlock)) {
                continue;
            }

            $result[] = $facetBlock;
        }

        return $result;
    }

    /**
     * @param Emico_Tweakwise_Block_Catalog_Layer_Facet_Attribute $facetBlock
     * @return bool
     */
    public function showFacetState(Emico_Tweakwise_Block_Catalog_Layer_Facet_Attribute $facetBlock)
    {
        if ($facetBlock instanceof Emico_Tweakwise_Block_Catalog_Layer_Facet_Category && $this->categoryAsLink()) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function categoryAsLink()
    {
        return Mage::helper('emico_tweakwise')->categoryAsLink();
    }
}