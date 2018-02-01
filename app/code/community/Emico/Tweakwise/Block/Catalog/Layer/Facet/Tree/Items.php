<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Block_Catalog_Layer_Facet_Tree_Items
 */
class Emico_Tweakwise_Block_Catalog_Layer_Facet_Tree_Items extends Emico_Tweakwise_Block_Catalog_Layer_Facet_Tree
{
    /**
     * {@inheritDoc}
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('emico_tweakwise/catalog/layer/facet/tree/items.phtml');
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Attribute[]
     */
    public function getAttributes()
    {
        if ($this->hasData('attributes')) {
            return $this->getData('attributes');
        }

        return $this->getFacet()->getAttributes();
    }

    /**
     * @param Emico_Tweakwise_Model_Bus_Type_Attribute $item
     * @return string
     */
    public function getChildrenTreeHtml(Emico_Tweakwise_Model_Bus_Type_Attribute $item)
    {
        /** @var Emico_Tweakwise_Block_Catalog_Layer_Facet_Tree_Items $block */
        $block = $this->getLayout()->createBlock('emico_tweakwise/catalog_layer_facet_tree_items');
        $block->setFacet($this->getFacet());
        $block->setAttributes($item->getChildren());

        return $block->toHtml();
    }

    /**
     * @param Emico_Tweakwise_Model_Bus_Type_Attribute $item
     * @return bool
     */
    public function showItem(Emico_Tweakwise_Model_Bus_Type_Attribute $item)
    {
        if ($this->getShowOnlyActiveCategories() && (!$item->getIsSelected() && !$item->getRender())) {
            return false;
        }

        return $this->categoryShowInMenu($item);
    }

    /**
     * Returns true if only shows active categories
     *
     * @param int|string|Mage_Core_Model_Store $store
     * @return boolean
     */
    public function getShowOnlyActiveCategories($store = null)
    {
        return Mage::getStoreConfig('emico_tweakwise/navigation/only_active_categories', $store);
    }

    /**
     * @param Emico_Tweakwise_Model_Bus_Type_Attribute $item
     * @return bool
     */
    public function categoryShowInMenu(Emico_Tweakwise_Model_Bus_Type_Attribute $item)
    {
        $helper = Mage::helper('emico_tweakwise');
        $category = $helper->getFilterCategory($item->getAttributeId());

        return $category->getData('include_in_menu');
    }

    /**
     * @param Emico_Tweakwise_Model_Bus_Type_Attribute $item
     * @return bool
     */
    public function isShowLink($item)
    {

        if ($item->getIsSelected() && !$this->isCheckbox() && empty($item->getData('children'))) {
            return false;
        }

        return true;
    }
}