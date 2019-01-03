<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */
class Emico_Tweakwise_Model_UrlBuilder_Strategy_CategoryStrategy implements Emico_Tweakwise_Model_UrlBuilder_Strategy_StrategyInterface
{
    /**
     * @var Mage_Catalog_Model_Category
     */
    protected $_currentCategory;

    /**
     * Builds the URL for a facet attribute
     *
     * @param Emico_Tweakwise_Model_Catalog_Layer $state
     * @param Emico_Tweakwise_Model_Bus_Type_Attribute $attribute
     * @return null|string
     */
    public function buildUrl(Emico_Tweakwise_Model_Catalog_Layer $state, Emico_Tweakwise_Model_Bus_Type_Facet $facet = null, Emico_Tweakwise_Model_Bus_Type_Attribute $attribute = null)
    {
        if ($facet === null || !$facet->isCategory() || $attribute === null) {
            return null;
        }

        $helper = Mage::helper('emico_tweakwise');
        $query = $this->getFilteredQuery();
        $category = $helper->getFilterCategory($attribute->getAttributeId());
        $query['p'] = null;
        $query['ajax'] = null;
        unset($query['cat'], $query['catid']);

        if ($helper->categoryAsLink()) {
            $url = $category->getUrl();
        } else {
            $attributeParams = [];
            parse_str(parse_url($attribute->getUrl(), PHP_URL_QUERY), $attributeParams);
            if (isset($attributeParams['tn_cid'])) {
                $categoryIds = explode('-', $attributeParams['tn_cid']);
                if ($attribute->getIsSelected()) {
                    array_pop($categoryIds);
                }
            } else {
                $categoryIds = [];
            }

            $currentCategory = $this->getCurrentCategory();
            $categories = [];
            foreach ($categoryIds as $categoryId) {
                $category = $helper->getFilterCategory($categoryId);
                if (!$currentCategory || $currentCategory->getId() !== $category->getId()) {
                    $categories[] = $category->getUrlKey();
                }
            }

            $query['cat'] = implode('|', $categories);
            if ($currentCategory && !$helper->isSearchPage()) {
                $url = $currentCategory->getUrl();
            } else {
                $url = Mage::getUrl('*/*/*');
            }
        }

        $query = array_filter($query);
        if ($query) {
            $url .= '?' . http_build_query($query);
        }

        if ($helper->categoryAsLink()) {
            $url .= '#no-ajax';
        }

        return $url;
    }

    /**
     * @param Zend_Controller_Request_Http $request
     * @return Emico_Tweakwise_Model_Bus_Request_Navigation
     */
    public function decorateTweakwiseRequest(Zend_Controller_Request_Http $httpRequest, Emico_Tweakwise_Model_Bus_Request_Navigation $tweakwiseRequest)
    {
        // Set category
        if (!$tweakwiseRequest->hasCategoryParam()) {
            $this->setDefaultRequestCategory($tweakwiseRequest);
        }

        if (!empty($httpRequest->getParam('cat'))) {
            $this->addCategoryQueryParam($tweakwiseRequest, $httpRequest->getParam('cat'));
        }

        return $tweakwiseRequest;
    }

    /**
     * @param Emico_Tweakwise_Model_Bus_Request_Navigation $request
     * @param $value
     * @return Emico_Tweakwise_Model_Catalog_Layer
     */
    protected function addCategoryQueryParam(Emico_Tweakwise_Model_Bus_Request_Navigation $request, $value)
    {
        $currentCategory = $this->getCurrentCategory();

        $categoryUrlKeys = explode('|', $value);
        $categories = [$currentCategory];

        // TODO optimize this this is allot of queries for a single category select
        foreach ($categoryUrlKeys as $categoryKey) {
            $categoryCollection = Mage::getResourceModel('catalog/category_collection');
            $categoryCollection->addFieldToFilter('url_key', $categoryKey);
            $categoryCollection->addIsActiveFilter();
            $categoryCollection->joinUrlRewrite();
            $categoryCollection->addFieldToFilter('parent_id', $currentCategory->getId());
            $categoryCollection->setPageSize(1);

            if ($categoryCollection->count() == 0) {
                break;
            }

            $currentCategory = $categoryCollection->getFirstItem();
            $categories[] = $currentCategory;
        }

        foreach ($categories as $category) {
            $request->addCategory($category);
        }
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
     * @param Emico_Tweakwise_Model_Bus_Request_Navigation $request
     * @return Emico_Tweakwise_Model_Catalog_Layer
     */
    protected function setDefaultRequestCategory(Emico_Tweakwise_Model_Bus_Request_Navigation $request)
    {
        $category = $this->getCurrentCategory();
        $helper = Mage::helper('emico_tweakwise');
        if (!$helper->categoryAsLink()) {
            $request->addCategory($category);
        } else {
            $categories = $category->getParentIds();
            array_push($categories, $category->getId());
            $startCategory = $helper->getCategoryTreeStartDepth();
            if ($maxLevels = $helper->getMaxTreeLevels()) {
                $startCategory = ((count($categories) - $maxLevels) > 1) ? ((count($categories) - $maxLevels)) : 1;
            }
            while (count($categories) <= $startCategory) {
                $startCategory--;
            }

            $categories = array_splice($categories, $startCategory);
            foreach ($categories as $category) {
                $request->addCategory($category);
            }
        }
    }

    /**
     * @return array
     */
    protected function getFilteredQuery()
    {
        $query = Mage::app()->getRequest()->getQuery();
        if (!$query || empty($query)) {
            return [];
        }
        try {
            $store = Mage::app()->getStore();
        } catch (Mage_Core_Model_Store_Exception $e) {
            $store = null;
        }
        $ignoredQueryParameters = Mage::helper('emico_tweakwise')
            ->getIgnoredQueryParameters($store);
        return array_diff_key($query, array_flip($ignoredQueryParameters));
    }

    /**
     * @param Emico_Tweakwise_Model_Catalog_Layer $state
     * @return mixed
     */
    public function buildCanonicalUrl(Emico_Tweakwise_Model_Catalog_Layer $state)
    {
        return $this->buildUrl($state);
    }
}