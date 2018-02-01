<?php

/**
 * @author Freek Gruntjes <fgruntjes@emico.nl>
 * @copyright (c) Emico B.V. 2016
 */

/**
 * Class Emico_Tweakwise_Block_Catalog_Product_List_Featured
 *
 * @method $this setRuleId(int $ruleId);
 * @method int getRuleId();
 * @method $this setProduct(Mage_Catalog_Model_Product $product);
 * @method Mage_Catalog_Model_Product getProduct();
 * @method $this setDisableRequestHandles(bool $flag);
 * @method bool getDisableRequestHandles();
 * @method $this setOriginalTemplate(string $template);
 * @method string getOriginalTemplate();
 */
class Emico_Tweakwise_Block_Catalog_Product_List_Featured extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * @var Emico_Tweakwise_Model_Data_Recommendation_Collection
     */
    protected $_itemCollection;

    /**
     * @return Emico_Tweakwise_Model_Data_Recommendation_Collection|Mage_Catalog_Model_Product[]
     */
    public function getItemCollection()
    {
        if (!$this->_itemCollection) {
            $this->_itemCollection = Mage::getModel('emico_tweakwise/data_recommendation_collection');
            $this->_itemCollection->setProduct($this->getProduct());
            $this->_itemCollection->setRuleId($this->getRuleId());

            /** @var Mage_Catalog_Model_Product $product */
            foreach ($this->_itemCollection as $product) {
                $product->setDoNotUseCategoryId(true);
            }
        }

        return $this->_itemCollection;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setDisableRequestHandles(true);
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
            $this->setOriginalTemplate($this->getTemplate());
            $this->setTemplate('emico_tweakwise/catalog/product/list/ajax-wrapper.phtml');
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
}