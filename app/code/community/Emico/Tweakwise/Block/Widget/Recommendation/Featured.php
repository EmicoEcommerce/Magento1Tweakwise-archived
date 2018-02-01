<?php
/**
 * @author Freek Gruntjes <fgruntjes@emico.nl>
 * @copyright (c) Emico B.V. 2016
 */

/**
 * Class Emico_Tweakwise_Block_Widget_Recommendation_Featured
 *
 * @method $this setRuleId(int $ruleId);
 * @method int getRuleId();
 */
class Emico_Tweakwise_Block_Widget_Recommendation_Featured extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{
    /**
     * @var Emico_Tweakwise_Model_Data_Recommendation_Collection|Mage_Catalog_Model_Product[]
     */
    protected $_itemCollection;

    /**
     * {@inheritdoc}
     */
    public function _construct()
    {
        parent::_construct();
        if (!$this->getTemplate()) {
            $this->setTemplate('emico_tweakwise/catalog/product/recommendations.phtml');
        }
    }

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
            ['_query' => ['rule' => $this->getRuleId()]]
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $this->_prepareData();
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function _prepareData()
    {
        $this->_itemCollection = Mage::getModel('emico_tweakwise/data_recommendation_collection');
        $this->_itemCollection->setRuleId($this->getRuleId());

        /** @var Mage_Catalog_Model_Product $product */
        foreach ($this->_itemCollection as $product) {
            $product->setDoNotUseCategoryId(true);
        }

        return $this;
    }
}
