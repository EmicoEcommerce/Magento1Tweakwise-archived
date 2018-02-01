<?php

/**
 * @author Freek Gruntjes <fgruntjes@emico.nl>
 * @copyright (c) Emico B.V. 2016
 */

/**
 * Class Emico_Tweakwise_Block_Catalog_Product_Recommendations
 *
 * @method $this setRuleId(int $ruleId);
 * @method int getRuleId();
 */
class Emico_Tweakwise_Block_Catalog_Product_Recommendations extends Mage_Catalog_Block_Product_List_Upsell
{

    /**
     * @return Emico_Tweakwise_Model_Data_Recommendation_Collection|Mage_Catalog_Model_Product[]
     */
    public function getProductCollection()
    {
        return $this->_itemCollection;
    }

    /**
     * @return string
     */
    public function getAjaxUrl()
    {
        return Mage::getUrl('tweakwise/recommendation/index',
            ['_query' => ['product' => $this->getProduct()->getSku(), 'rule' => $this->getRuleId()]]
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function _prepareData()
    {
        $store = Mage::app()->getStore();
        $helper = Mage::helper('emico_tweakwise');
        if ($helper->isRecommendationsAjax($store)) {
            return $this;
        }

        $product = Mage::registry('product');
        $this->_itemCollection = Mage::getModel('emico_tweakwise/data_recommendation_collection');
        $this->_itemCollection->setProduct($product);


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
}