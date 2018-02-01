<?php
/**
 * @author Freek Gruntjes <fgruntjes@emico.nl>
 * @copyright (c) Emico B.V. 2016
 */

/**
 * Class Emico_Tweakwise_Block_Checkout_Cart_Crosssell
 *
 * @method $this setRuleId(int $ruleId);
 */
class Emico_Tweakwise_Block_Checkout_Cart_Crosssell extends Mage_Checkout_Block_Cart_Crosssell
{
    /**
     * {@inheritdoc}
     */
    public function getItems()
    {
        $items = $this->getData('items');
        if ($items !== null) {
            return $items;
        }

        if (!$this->isTweakwiseEnabled() || !$this->getRuleId()) {
            return parent::getItems();
        }

        if ($this->isAjaxEnabled() && !$this->isAjaxRequest()) {
            return $this;
        }

        $items = Mage::getModel('emico_tweakwise/data_recommendation_collection');
        $items->setPageSize($this->_maxItemCount);
        $items->setRuleId($this->getRuleId());

        /** @var Mage_Catalog_Model_Product $product */
        foreach ($items as $product) {
            $product->setDoNotUseCategoryId(true);
        }

        $this->setData('items', $items);
        return $items;
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
        return (int)Mage::getStoreConfig('emico_tweakwise/recommendations/crosssell_template');
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