<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Model_Bus_Request_Navigation
 */
class Emico_Tweakwise_Model_Bus_Request_Recommendations_Product extends Emico_Tweakwise_Model_Bus_Request_Abstract
{
    /**
     * @var int
     */
    protected $_ruleId;

    /**
     * @var int
     */
    protected $_productId;

    /**
     * @param Mage_Catalog_Model_Product|int|string $product
     * @return $this
     */
    public function setProduct($product)
    {
        if (is_string($product) && !is_numeric($product)) {
            /** @var Mage_Catalog_Model_Product $model */
            $model = Mage::getModel('catalog/product');
            $product = $model->getIdBySku($product);
        } elseif ($product instanceof Mage_Catalog_Model_Product) {
            $product = $product->getId();
        }

        $this->_productId = (int)$product;
        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return
     */
    public function execute($store = null)
    {
        /** @var Emico_TweakwiseExport_Helper_Data $helper */
        $helper = Mage::helper('emico_tweakwiseexport');
        $productId = $helper->toStoreId($store, $this->getProductId());
        $ruleId = $this->getRuleId();

        if ($ruleId && is_int($ruleId)) {
            $this->setClientUrl('{baseUrl}/{service}/{key}/{ruleId}/{productId}', ['ruleId' => $ruleId, 'productId' => $productId]);
        } elseif ($ruleId && is_string($ruleId)) {
            // Apparently the last two path segments are reversed in tweakwise api when dealing with a grouped request
            $this->setClientUrl('{baseUrl}/{service}/{key}/{productId}/{ruleId}', ['ruleId' => $ruleId, 'productId' => $productId]);
        } else {
            $this->setClientUrl('{baseUrl}/{service}/{key}/{productId}', ['productId' => $productId]);
        }

        return parent::execute($store);
    }

    /**
     * @return int
     */
    public function getProductId()
    {
        return $this->_productId;
    }

    /**
     * @return int
     */
    public function getRuleId()
    {
        return $this->_ruleId;
    }

    /**
     * @param int $ruleId
     * @return $this
     */
    public function setRuleId($ruleId)
    {
        $this->_ruleId = $ruleId;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function getResponseModel()
    {
        return 'response_recommendations';
    }

    /**
     * {@inheritDoc}
     */
    protected function getServiceKey()
    {
        return is_int($this->_ruleId) ? 'recommendations/product' : 'recommendations/grouped';
    }
}
