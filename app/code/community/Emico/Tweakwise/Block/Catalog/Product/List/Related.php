<?php
/**
 * @author Freek Gruntjes <fgruntjes@emico.nl>
 * @copyright (c) Emico B.V. 2016
 */

/**
 * Class Emico_Tweakwise_Block_Catalog_Product_List_Related
 *
 * @method $this setRuleId(int $ruleId);
 */
class Emico_Tweakwise_Block_Catalog_Product_List_Related extends Mage_Catalog_Block_Product_List_Related
{
    /**
     * {@inheritdoc}
     */
    public function _prepareData()
    {
        if (!$this->isTweakwiseEnabled() || !$this->getRuleId()) {
            return parent::_prepareData();
        }

        if ($this->isAjaxEnabled() && !$this->isAjaxRequest()) {
            return $this;
        }

        $product = Mage::registry('product');
        $this->_itemCollection = Mage::getModel('emico_tweakwise/data_recommendation_collection');
        $this->_itemCollection->setProduct($product);
        $this->_itemCollection->setRuleId($this->getRuleId());

        /** @var Mage_Catalog_Model_Product $product */
        foreach ($this->_itemCollection as $product) {
            $product->setDoNotUseCategoryId(true);
        }

        return $this;
    }

    /**
     * @return bool
     */
    protected function isTweakwiseEnabled()
    {
        return Mage::helper('emico_tweakwise')->isRecommendationsEnabled();
    }

    /**
     * @return int
     */
    protected function getRuleId()
    {
        $product = $this->getProduct();
        $crossSellTemplateAttribute = Emico_Tweakwise_Helper_Data::RELATED_TEMPLATE_ATTRIBUTE;
        $ruleId = (int)$product->getData($crossSellTemplateAttribute);
        if ($ruleId) {
            return $ruleId;
        }
        $category = Mage::registry('current_category');
        if ($category) {
            $ruleId = (int)$category->getData($crossSellTemplateAttribute);
            while (!$ruleId && $category->getParentId() && $category->getParentId() !== 1) {
                $category = $category->getParentCategory();
                $ruleId = (int)$category->getData($crossSellTemplateAttribute);
            }
        }
        if ($ruleId) {
            return $ruleId;
        }

        return (int)Mage::getStoreConfig('emico_tweakwise/recommendations/product_related_template');
    }

    /**
     * @return bool
     */
    protected function isAjaxEnabled()
    {
        return Mage::helper('emico_tweakwise')->isRecommendationsAjax();
    }

    /**
     * @return bool
     */
    protected function isAjaxRequest()
    {
        return Mage::app()->getRequest()->isAjax();
    }

    /**
     * {@inheritdoc}
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();

        if (!$this->isTweakwiseEnabled() || !$this->getRuleId()) {
            return $this;
        }

        if ($this->isAjaxEnabled() && !$this->isAjaxRequest()) {
            $this->setTemplate('emico_tweakwise/catalog/product/list/ajax-wrapper.phtml');
        }

        return $this;
    }
}