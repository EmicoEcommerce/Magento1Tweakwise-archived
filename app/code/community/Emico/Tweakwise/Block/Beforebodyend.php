<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Give autocomplete a category without rewriting form.mini search block.
 */
class Emico_Tweakwise_Block_Beforebodyend extends Mage_Core_Block_Template
{
    /**
     * {@inheritDoc}
     */
    public function _toHtml()
    {
        return Mage::helper('emico_tweakwise')->isEnabled('autocomplete') ? parent::_toHtml() : '';
    }

    /**
     * @return int
     */
    protected function getTweakwiseCategoryId()
    {
        $helperExport = Mage::helper('emico_tweakwiseexport');

        return $helperExport->toStoreId(Mage::app()->getStore(), $this->getCategoryId());
    }

    /**
     * Get currently set category
     *
     * @return int
     */
    protected function getCategoryId()
    {
        $helper = Mage::helper('emico_tweakwise');

        if ($helper->stayInCategory()) {
            $category = Mage::registry('current_category');
            if (!$category) {
                $categoryId = Mage::app()->getStore()->getRootCategoryId();
            } else {
                $categoryId = $category->getId();
            }
        } else {
            $categoryId = Mage::app()->getStore()->getRootCategoryId();
        }

        return $categoryId;
    }
}