<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Model_Catalog_Layer
 */
class Emico_Tweakwise_Model_Catalog_Layer
{
    /**
     * @var Emico_Tweakwise_Model_Bus_Request_Autocomplete
     */
    protected $_tweakwiseRequest;

    /**
     * @var Emico_Tweakwise_Model_Bus_Type_Response_Autocomplete
     */
    protected $_tweakwiseResponse;

    /**
     * @var Mage_Catalog_Model_Resource_Product_Collection
     */
    protected $_productCollection;

    /**
     * @var Emico_Tweakwise_Model_Catalog_Layer_Filter
     */
    protected $_filterableAttributes;

    /**
     * @var Mage_Catalog_Model_Category
     */
    protected $_currentCategory;

    /**
     * @var int
     */
    protected $_templateId;

    /**
     * @var array
     */
    protected $_selectedFacets = [];

    /**
     * @return Mage_Catalog_Model_Product[]|Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getProducts()
    {
        if ($this->_productCollection === null) {
            $this->_productCollection = $this->initProductCollection();
        }

        return $this->_productCollection;
    }

    /**
     * @return Varien_Data_Collection|Mage_Catalog_Model_Product[]
     */
    protected function initProductCollection()
    {
        $items = $this->getTweakwiseResponse()->getItems();

        $helper = Mage::helper('emico_tweakwise');
        if ($helper->isDirectDataEnabled()) {
            return $this->getDirectProductCollection($items);
        } else {
            return $this->getProductCollection($items);
        }
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Response_Navigation
     */
    public function getTweakwiseResponse()
    {
        if ($this->_tweakwiseResponse === null) {
            $request = $this->getTweakwiseRequest();
            Mage::dispatchEvent('tweakwise_dispatch_request_before', ['request' => $request, 'layer' => $this]);

            Varien_Profiler::start('tweakwise_request');
            $this->_tweakwiseResponse = $request->execute();
            Varien_Profiler::stop('tweakwise_request');

            Mage::dispatchEvent('tweakwise_dispatch_request_after', [
                'request' => $request,
                'layer' => $this,
                'response' => $this->_tweakwiseResponse,
            ]);

            if ($this->getTweakwiseRequest()->getError()) {
                Mage::app()->getResponse()->setHttpResponseCode(500);
            }
        }

        return $this->_tweakwiseResponse;
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Request_Abstract
     */
    public function getTweakwiseRequest()
    {
        if ($this->_tweakwiseRequest === null) {
            $this->_tweakwiseRequest = $this->createTweakwiseRequest();
            Mage::dispatchEvent('tweakwise_create_request_after', [
                'request' => $this->_tweakwiseRequest,
                'layer' => $this,
            ]);
        }

        return $this->_tweakwiseRequest;
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Request_Navigation
     */
    protected function createTweakwiseRequest()
    {
        $uriStrategyHelper = Mage::helper('emico_tweakwise/uriStrategy');

        $request = Mage::getModel('emico_tweakwise/bus_request_navigation');

        $httpRequest = Mage::app()->getRequest();

        if ($this->getTemplateId()) {
            $request->setTemplateId($this->getTemplateId());
        }

        if ($httpRequest) {
            $this->applySessionParams($request);

            foreach ($uriStrategyHelper->getActiveStrategies() as $strategy) {
                $request = $strategy->decorateTweakwiseRequest($httpRequest, $request);
            }

            foreach ($httpRequest->getParams() as $key => $value) {
                if (!is_scalar($value) && !is_array($value)) {
                    continue;
                }

                if (!$this->applyQueryParam($request, $httpRequest, $key, $value)) {
                    $request->addFacetKey($key, $value);
                }
            }

            if (!$request->getProductsPerPage()) {
                /** @var $block Mage_Catalog_Block_Product_List_Toolbar */
                $block = Mage::app()->getLayout()->createBlock('catalog/product_list_toolbar');
                $request->setProductsPerPage($block->getLimit());
            }
        }

        return $request;
    }

    /**
     * @return int
     */
    public function getTemplateId()
    {
        return $this->_templateId;
    }

    /**
     * @param int $templateId
     *
     * @return $this
     */
    public function setTemplateId($templateId)
    {
        $this->_templateId = (int)$templateId;

        return $this;
    }

    /**
     * @param Emico_Tweakwise_Model_Bus_Request_Navigation $request
     */
    protected function applySessionParams(Emico_Tweakwise_Model_Bus_Request_Navigation $request)
    {
        $session = Mage::getSingleton('catalog/session');
        foreach ($session->getData() as $key => $value) {
            switch ($key) {
                case 'sort_order':
                    if (strtolower($session->getData('sort_direction')) == 'desc') {
                        $value = '-' . $value;
                    }
                    $request->setSort($value);
                    break;
                case 'limit_page':
                    $request->setProductsPerPage($value);
                    break;
            }
        }
    }

    /**
     * @param Emico_Tweakwise_Model_Bus_Request_Navigation $request
     * @param Mage_Core_Controller_Request_Http $httpRequest
     * @param string $param
     * @param string $value
     * @return bool
     */
    protected function applyQueryParam(Emico_Tweakwise_Model_Bus_Request_Navigation $request, Mage_Core_Controller_Request_Http $httpRequest, $param, $value)
    {
        if ($param == 'order') {
            if (strtolower($httpRequest->getParam('dir')) == 'desc') {
                $value = '-' . $value;
            }
            $request->setSort($value);
        } else {
            if ($param == 'limit') {
                $request->setProductsPerPage($value);
            } else {
                if ($param == 'p') {
                    $request->setPage($value);
                } else {
                    if ($param == 'q') {
                        $request->setSearchQuery($value);
                    } else {
                        if ($param != 'mode' && $param != 'dir') {
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * @return Mage_Catalog_Model_Category
     */
    public function getCurrentCategory()
    {
        if ($this->_currentCategory === null) {
            if ($category = Mage::registry('current_category')) {
                $this->_currentCategory = $category;
            } else {
                $categoryId = Mage::app()->getStore()->getRootCategoryId();
                $this->_currentCategory = Mage::getModel('catalog/category')->load($categoryId);
            }
        }
        return $this->_currentCategory;
    }

    /**
     * @param $items
     * @return Varien_Data_Collection
     */
    private function getDirectProductCollection($items)
    {
        $collection = $this->createItemDataCollection();

        if ($items && is_array($items)) {
            foreach ($items as $item) {
                if ($item instanceof Emico_Tweakwise_Model_Bus_Type_Item) {
                    $oldPrice = null;
                    if ($item->getLabels()->getLabel1()) {
                        $oldPrice = $item->getLabels()->getLabel1();
                    }

                    $product = Mage::getModel('catalog/product')
                        ->setData('name', $item->getTitle())
                        ->setData('final_price', $item->getPrice())
                        ->setData('url', Mage::getBaseUrl() . $item->getUrl())
                        ->setData('small_image', $item->getImage())
                        ->addData($item->getLabels()->getArray())
                        ->setId(Mage::helper('emico_tweakwiseexport')->fromStoreId($item->getId()));

                    if ($oldPrice) {
                        $product->setData('price', $oldPrice);
                    }
                    $collection->addItem($product);
                }
            }
        }

        return $collection;
    }

    /**
     * @return Emico_Tweakwise_Model_Data_Collection
     */
    protected function createItemDataCollection()
    {
        $collection = new Emico_Tweakwise_Model_Data_Collection();
        $response = $this->getTweakwiseResponse();

        if (!$this->getTweakwiseResponse() instanceof Emico_Tweakwise_Model_Bus_Type_Response_Autocomplete) {
            $properties = $response->getProperties();

            $collection->setPageSize($properties->getPageSize());
            $collection->setCurPage($properties->getCurrentPage());
            $collection->setTotalRecords($properties->getNumberOfItems());
        }
        return $collection;
    }

    /**
     * @param $items
     * @return Varien_Data_Collection
     */
    private function getProductCollection($items)
    {
        $ids = [];
        if ($items && is_array($items)) {
            foreach ($items as $item) {
                if ($item instanceof Emico_Tweakwise_Model_Bus_Type_Item) {
                    $ids[] = Mage::helper('emico_tweakwiseexport')->fromStoreId($item->getId());
                }
            }
        }

        return Mage::helper('emico_tweakwise')->getProductCollection($ids, $this->createItemDataCollection());
    }

    /**
     * @return int
     */
    public function getProductCount()
    {
        return $this->getTweakwiseResponse()->getProperties()->getNumberOfItems();
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Facet[]
     */
    public function getFacets()
    {
        return $this->getTweakwiseResponse()->getFacets();
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Attribute[]
     */
    public function getSelectedAttributes()
    {
        $selectedAttributes = [];
        foreach ($this->getFacets() as $facet) {
            foreach ($facet->getAttributes() as $attribute) {
                if ($attribute->getIsSelected()) {
                    $selectedAttributes[] = $attribute;
                }
            }
        }
        return $selectedAttributes;
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Facet[]
     */
    public function getSelectedFacets()
    {
        if (empty($this->_selectedFacets)) {
            foreach ($this->getFacets() as $facet) {
                foreach ($facet->getAttributes() as $attribute) {
                    if ($attribute->getIsSelected()) {
                        $this->_selectedFacets[$facet->getFacetSettings()->getFacetId()] = $facet;
                    }
                }
            }
        }

        return $this->_selectedFacets;
    }

    /**
     * @return bool
     */
    public function hasTweakwiseResponse()
    {
        return (bool)$this->_tweakwiseResponse;
    }
}
