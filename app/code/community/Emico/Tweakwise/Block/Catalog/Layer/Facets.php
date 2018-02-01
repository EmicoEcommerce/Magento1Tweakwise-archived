<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Block_Catalog_Layer_Facets
 */
class Emico_Tweakwise_Block_Catalog_Layer_Facets extends Emico_Tweakwise_Block_Catalog_Layer_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function _prepareLayout()
    {
        if (!Mage::helper('emico_tweakwise')->isEnabled('navigation')) {
            parent::_prepareLayout();

            return;
        }

        foreach ($this->getVisibleFacets() as $facet) {
            if ($facet->isTree()) {
                $type = 'emico_tweakwise/catalog_layer_facet_tree';
            } else {
                if ($facet->isCategory()) {
                    $type = 'emico_tweakwise/catalog_layer_facet_category';
                } else {
                    if ($facet->isSlider()) {
                        $type = 'emico_tweakwise/catalog_layer_facet_slider';
                    } else {
                        if ($facet->isColor()) {
                            $type = 'emico_tweakwise/catalog_layer_facet_color';
                        } else {
                            $type = 'emico_tweakwise/catalog_layer_facet_attribute';
                        }
                    }
                }
            }

            $blockAlias = $this->getNameInLayout() . '.' . $facet->getFacetSettings()->getFacetId();
            /** @var Emico_Tweakwise_Block_Catalog_Layer_Facet_Attribute $block */
            if (!$block = $this->getLayout()->createBlock($type)) {
                throw new RuntimeException('Could not create block of type ' . $type);
            }

            if (!$block instanceof Emico_Tweakwise_Block_Catalog_Layer_Facet_Attribute) {
                throw new RuntimeException('Block ' . get_class($block) . ' is not an instance of Emico_Tweakwise_Block_Catalog_Layer_Facet_Attribute');
            }
            $block->setFacet($facet);
            $this->setChild($blockAlias, $block);
        }
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Facet[]
     */
    public function getVisibleFacets()
    {
        $facets = [];
        foreach ($this->getLayer()->getFacets() as $facet) {
            if ($facet->getFacetSettings()->getIsVisible()) {
                $facets[] = $facet;
            }
        }

        return $facets;
    }

    /**
     * @return Emico_Tweakwise_Model_Catalog_Layer
     */
    public function getLayer()
    {
        return Mage::getSingleton('emico_tweakwise/catalog_layer');
    }

    /**
     * @return Emico_Tweakwise_Block_Catalog_Layer_Facet_Attribute[]
     */
    public function getActive()
    {
        $active = [];
        /** @var $facet Emico_Tweakwise_Block_Catalog_Layer_Facet_Attribute */
        foreach ($this->getFacetBlocks() as $facet) {
            if ($facet->getFacet()->isActive()) {
                $active[] = $facet;
            }
        }

        return $active;
    }

    /**
     * @return Emico_Tweakwise_Block_Catalog_Layer_Facet_Attribute[]
     */
    public function getFacetBlocks()
    {
        return $this->getChild();
    }
}