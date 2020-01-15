<?php
/**
 * @author Freek Gruntjes <fgruntjes@emico.nl>
 * @copyright (c) Emico B.V. 2016
 */

/**
 * Class Emico_Tweakwise_Block_Catalog_Product_List_Upsell
 *
 * @method $this setRuleId(int $ruleId);
 */
class Emico_Tweakwise_Block_Catalog_Product_List_Upsell extends Mage_Catalog_Block_Product_List_Upsell
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


        if ($this->getItemLimit('upsell') > 0) {
            $this->_itemCollection->setPageSize($this->getItemLimit('upsell'));
        }

        /**
         * Updating collection with desired items
         */
        Mage::dispatchEvent('catalog_product_upsell', [
            'product' => $product,
            'collection' => $this->_itemCollection,
            'limit' => $this->getItemLimit(),
        ]);

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
        $upsellTemplateAttribute = Emico_Tweakwise_Helper_Data::UPSELL_TEMPLATE_ATTRIBUTE;
        $ruleId = (int)$product->getData($upsellTemplateAttribute);
        if ($ruleId) {
            return $ruleId;
        }

        $category = Mage::registry('current_category');
        if ($category) {
            $ruleId = (int)$category->getData($upsellTemplateAttribute);
            while (!$ruleId && $category->getParentId() && $category->getParentId() !== 1) {
                $category = $category->getParentCategory();
                $ruleId = (int)$category->getData($upsellTemplateAttribute);
            }
        }

        if ($ruleId) {
            return $ruleId;
        }

        $defaultUpsellTemplate = (int)Mage::getStoreConfig('emico_tweakwise/recommendations/product_upsell_template');
        if ($defaultUpsellTemplate === -1) {
            return Mage::getStoreConfig('emico_tweakwise/recommendations/upsell_group_code');
        }

        return $defaultUpsellTemplate;
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
