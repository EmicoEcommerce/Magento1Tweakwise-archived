<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */
class Emico_Tweakwise_Model_UrlBuilder_Strategy_PathStrategy implements
    Emico_Tweakwise_Model_UrlBuilder_Strategy_StrategyInterface,
    Emico_Tweakwise_Model_UrlBuilder_Strategy_RoutingStrategyInterface
{
    /**
     * @var string
     */
    protected $_baseUrl;

    /**
     * @var Mage_Core_Model_Url
     */
    protected $_urlModel;

    /**
     * @var Emico_Tweakwise_Model_UrlBuilder_Strategy_StrategyInterface
     */
    protected $_fallbackStrategy;

    /**
     * Emico_Tweakwise_Model_UrlBuilder_Strategy_PathStrategy constructor.
     */
    public function __construct()
    {
        $this->_fallbackStrategy = Mage::getModel('emico_tweakwise/urlBuilder_strategy_queryParamStrategy');
    }

    /**
     * Builds the URL for a facet attribute
     *
     * @param Emico_Tweakwise_Model_Catalog_Layer $state
     * @param Emico_Tweakwise_Model_Bus_Type_Attribute $attribute
     * @return null|string
     */
    public function buildUrl(Emico_Tweakwise_Model_Catalog_Layer $state, Emico_Tweakwise_Model_Bus_Type_Facet $facet = null, Emico_Tweakwise_Model_Bus_Type_Attribute $attribute = null)
    {
        if (!$this->isAllowedInCurrentContext()) {
            return $this->_fallbackStrategy->buildUrl($state, $facet, $attribute);
        }

        $url = $this->getBaseUrl();

        // Add the attribute filters to the URL path
        $url .= '/' . $this->buildAttributeUriPath($state, $facet, $attribute);

        // Apply query params
        $urlModel = $this->getUrlModel();
        $urlModel->setQueryParam('p', null);
        $urlModel->setQueryParam('ajax', null);
        $query = $urlModel->getQuery(false);
        if ($query) {
            $mark = (strpos($url, '?') === false) ? '?' : '&';
            $url .= $mark . $query;
        }

        return $url;
    }

    /**
     * @param Emico_Tweakwise_Model_Catalog_Layer $state
     * @param Emico_Tweakwise_Model_Bus_Type_Facet $facet
     * @param Emico_Tweakwise_Model_Bus_Type_Attribute $attribute
     * @return string
     */
    protected function buildAttributeUriPath(Emico_Tweakwise_Model_Catalog_Layer $state, Emico_Tweakwise_Model_Bus_Type_Facet $facet = null, Emico_Tweakwise_Model_Bus_Type_Attribute $attribute = null)
    {
        $facetAttributes = [];
        foreach ($state->getSelectedFacets() as $selectedFacet) {
            if ($selectedFacet->getFacetSettings()->getSelectionType() === Emico_Tweakwise_Model_Bus_Type_Facet_Settings::SELECTION_TYPE_SLIDER) {
                continue;
            }

            foreach ($selectedFacet->getActiveAttributes() as $activeAttribute) {
                if ($selectedFacet->isCategory() || $activeAttribute === $attribute) {
                    continue;
                }
                $facetSettings = $selectedFacet->getFacetSettings();
                $facetAttributes[$facetSettings->getUrlKey()][] = $this->getSlugAttributeMapper()->getSlugForAttribute(
                    $facetSettings->getCode(),
                    $activeAttribute->getTitle()
                );
            }
        }

        if ($facet !== null && $attribute !== null && !$attribute->getIsSelected()) {
            $facetSettings = $facet->getFacetSettings();
            $facetAttributes[$facetSettings->getUrlKey()][] = $this->getSlugAttributeMapper()->getSlugForAttribute(
                $facetSettings->getCode(),
                $attribute->getTitle()
            );
        }

        $path = '';
        foreach ($facetAttributes as $facetKey => $attributeSlugs) {
            /** @var Emico_Tweakwise_Model_Bus_Type_Attribute $facetAttribute */
            foreach ($attributeSlugs as $slug) {
                $path .= $facetKey . '/' . $slug . '/';
            }
        }

        $path = rtrim($path, '/');

        $query = Mage::app()->getRequest()->getQuery();
        if (count($query)) {
            $path .= '?' . http_build_query($query);
        }

        return $path;
    }

    /**
     * @return string
     */
    protected function getBaseUrl()
    {
        if ($this->_baseUrl === null) {

            if (Mage::registry('current_category')) {
                $categoryUrl = Mage::registry('current_category')->getUrl();
                $queryPosition = strpos($categoryUrl, '?');
                $this->_baseUrl = ($queryPosition > 0) ? substr($categoryUrl, 0, $queryPosition) : $categoryUrl;
            }
        }
        return rtrim($this->_baseUrl, '/');
    }

    /**
     * @param Zend_Controller_Request_Http $httpRequest
     * @param Emico_Tweakwise_Model_Bus_Request_Navigation $tweakwiseRequest
     * @return Emico_Tweakwise_Model_Bus_Request_Navigation
     * @internal param Zend_Controller_Request_Http $request
     */
    public function decorateTweakwiseRequest(Zend_Controller_Request_Http $httpRequest, Emico_Tweakwise_Model_Bus_Request_Navigation $tweakwiseRequest)
    {
        if (!$this->isAllowedInCurrentContext()) {
            return $this->_fallbackStrategy->decorateTweakwiseRequest($httpRequest, $tweakwiseRequest);
        }

        $filterPath = trim($httpRequest->getParam('filter_path'), '/');
        if (empty($filterPath)) {
            return $tweakwiseRequest;
        }

        $filterParts = explode('/', $filterPath);

        $facetKey = $filterParts[0];
        foreach ($filterParts as $i => $part) {
            if ($i % 2 === 0) {
                $facetKey = $part;
            } else {
                $facetValue = $this->getSlugAttributeMapper()->getAttributeValueBySlug($facetKey, $part);
                if (!empty($facetKey)) {
                    $tweakwiseRequest->addFacetKey($facetKey, $facetValue);
                }
            }
        }

        return $tweakwiseRequest;
    }

    /**
     * If you need to do custom routing implement this method
     *
     * @param Zend_Controller_Request_Http $request
     * @return bool
     */
    public function matchUrl(Zend_Controller_Request_Http $request)
    {
        $path = trim($request->getPathInfo(), '/');

        // See if a category rewrite exists for the current request
        $urlModel = Mage::getModel('core/url_rewrite');
        $urlModel->setStoreId(Mage::app()->getStore()->getId());
        $urlModel->loadByRequestPath(array_reverse($this->getPathsToCheck($path)));

        if ($urlModel->getId() === null || !$urlModel->getCategoryId()) {
            return false;
        }

        $request->setParam('filter_path', substr($path, strlen($urlModel->getRequestPath())));
        $request->setRequestUri($request->getBaseUrl() . '/' . $urlModel->getTargetPath());
        $request->setPathInfo($urlModel->getTargetPath());

        $request->setAlias(Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS, $path);

        return true;
    }

    /**
     * @param $fullUriPath
     * @return array
     */
    protected function getPathsToCheck($fullUriPath)
    {
        $pathParts = explode('/', $fullUriPath);
        $lastPathPart = array_shift($pathParts);
        $paths[] = $lastPathPart;
        foreach ($pathParts as $i => $pathPart) {
            $lastPathPart .= '/' . $pathPart;
            $paths[] = $lastPathPart;
        }
        return $paths;
    }

    /**
     * @return Emico_TweakwiseExport_Model_SlugAttributeMapping
     */
    protected function getSlugAttributeMapper()
    {
        return Mage::getSingleton('emico_tweakwiseexport/slugAttributeMapping');
    }

    /**
     * @return Mage_Core_Model_Url
     */
    protected function getUrlModel()
    {
        if ($this->_urlModel === null) {
            $this->_urlModel = Mage::getModel('core/url');
        }
        return $this->_urlModel;
    }

    /**
     * @return bool
     */
    protected function isAllowedInCurrentContext()
    {
        return (Mage::registry('current_category') !== null);
    }
}