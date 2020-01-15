<?php

/**
 * @author Freek Gruntjes <fgruntjes@emico.nl>
 * @copyright (c) Emico B.V. 2016
 */

/**
 * Class Emico_Tweakwise_Model_Data_Recommendation_Collection
 */
class Emico_Tweakwise_Model_Data_Recommendation_Collection extends Emico_Tweakwise_Model_Data_Collection
{
    /**
     * @var Mage_Catalog_Model_Product
     */
    protected $_product;

    /**
     * @var Emico_Tweakwise_Model_Bus_Type_Response_Recommendations
     */
    protected $_tweakwiseResponse;

    /**
     * @var int
     */
    protected $_ruleId;

    /**
     * @param bool $printQuery
     * @param bool $logQuery
     * @return $this
     */
    public function loadData($printQuery = false, $logQuery = false)
    {
        if ($this->isLoaded()) {
            return $this;
        }
        $this->_setIsLoaded(true);

        // If no rule ID is provided also no valid request will be send to TW
        if (!$this->getRuleId()) {
            return $this;
        }

        $response = $this->getTweakwiseResponse();
        if ($response) {
            $ids = [];

            $items = $response->getItems() ?: [];
            /** @var Emico_TweakwiseExport_Helper_Data $exportHelper */
            $exportHelper = Mage::helper('emico_tweakwiseexport');
            foreach ($items as $recommendation) {
                $ids[] = $exportHelper->fromStoreId($recommendation->getId());
            }

            Mage::helper('emico_tweakwise')->getProductCollection($ids, $this);
        }

        return $this;
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
     * @return Emico_Tweakwise_Model_Bus_Type_Response_Recommendations
     */
    public function getTweakwiseResponse()
    {
        if (!$this->_tweakwiseResponse) {
            $product = $this->getProduct();

            if ($product) {
                /** @var Emico_Tweakwise_Model_Bus_Request_Recommendations_Product $request */
                $request = Mage::getModel('emico_tweakwise/bus_request_recommendations_product');
                $request->setProduct($product);
                $request->setRuleId($this->getRuleId());
            } else {
                /** @var Emico_Tweakwise_Model_Bus_Request_Recommendations_Featured $request */
                $request = Mage::getModel('emico_tweakwise/bus_request_recommendations_featured');
                $request->setRuleId($this->getRuleId());
            }

            $this->_tweakwiseResponse = $request->execute();
        }

        return $this->_tweakwiseResponse;
    }

    /**
     * @param Emico_Tweakwise_Model_Bus_Type_Response_Recommendations $response
     * @return $this
     */
    public function setTweakwiseResponse(Emico_Tweakwise_Model_Bus_Type_Response_Recommendations $response)
    {
        $this->_tweakwiseResponse = $response;
        return $this;
    }

    /**
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        return $this->_product;
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @return $this
     */
    public function setProduct(Mage_Catalog_Model_Product $product = null)
    {
        $this->_product = $product;
        return $this;
    }
}
