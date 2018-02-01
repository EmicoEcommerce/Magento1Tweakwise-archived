<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Block_Catalog_Layer_Facet_Tree
 */
class Emico_Tweakwise_Block_Catalog_Layer_Facet_Tree extends Emico_Tweakwise_Block_Catalog_Layer_Facet_Category
{
    /**
     * {@inheritDoc}
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('emico_tweakwise/catalog/layer/facet/tree.phtml');
    }

    /**
     * {@inheritDoc}
     */
    protected function _beforeToHtml()
    {
        /** @var Emico_Tweakwise_Block_Catalog_Layer_Facet_Tree_Items $block */
        $block = $this->getLayout()->createBlock('emico_tweakwise/catalog_layer_facet_tree_items');
        $block->setFacet($this->getFacet());
        $block->setAttributes($this->getFacet()->getAttributes());
        $this->setChild('items', $block);
    }
} 