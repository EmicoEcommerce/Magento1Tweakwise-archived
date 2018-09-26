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
     * @var bool
     */
    protected $_isAllowedInCurrentContext;

    /**
     * @var Mage_Catalog_Model_Category
     */
    protected $_currentCategory;

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
        $slugs = [];
        $slugMapper = $this->getSlugAttributeMapper();
        foreach ($state->getSelectedFacets() as $selectedFacet) {
            if ($selectedFacet->getFacetSettings()->getSelectionType() === Emico_Tweakwise_Model_Bus_Type_Facet_Settings::SELECTION_TYPE_SLIDER) {
                continue;
            }

            foreach ($selectedFacet->getActiveAttributes() as $activeAttribute) {
                if ($selectedFacet->isCategory() || $activeAttribute === $attribute) {
                    continue;
                }
                $facetSettings = $selectedFacet->getFacetSettings();
                $slugs[] = [
                    'facet' => $facetSettings->getUrlKey(),
                    'value' => $slugMapper->getSlugForAttributeValue($activeAttribute->getTitle())
                ];
            }
        }

        if ($facet !== null && $attribute !== null && !$attribute->getIsSelected()) {
            $facetSettings = $facet->getFacetSettings();
            $slugs[] = [
                'facet' => $facetSettings->getUrlKey(),
                'value' => $slugMapper->getSlugForAttributeValue($attribute->getTitle())
            ];
        }

        return $this->getPathFromSlugs($slugs, $state);
    }

    /**
     * @param array $slugs
     * @param Emico_Tweakwise_Model_Catalog_Layer $state
     * @return string
     */
    protected function getPathFromSlugs(array $slugs, Emico_Tweakwise_Model_Catalog_Layer $state)
    {
        // Sort facets so we get canonical URL's for certain filter combinations
        $this->sortSlugs($slugs, $state);

        $path = '';
        foreach ($slugs as $slug) {
            $path .= $slug['facet'] . '/' . $slug['value'] . '/';
        }

        $path = rtrim($path, '/');

        return $path;
    }

    /**
     * @param array $slugA
     * @param array $slugB
     * @return int|null
     */
    protected function sortSlugs(array &$slugs, Emico_Tweakwise_Model_Catalog_Layer $state)
    {
        $facetSortList = [];
        foreach ($state->getFacets() as $facet) {
            $facetSortList[] = $facet->getFacetSettings()->getUrlKey();
        }

        usort($slugs, function($slugA, $slugB) use ($facetSortList) {
            if ($slugA['facet'] == $slugB['facet']) {
                // Sort values alphabetical
                if ($slugA['value'] == $slugB['value']) {
                    return null;
                }
                return ($slugA['value'] < $slugB['value']) ? -1 : 1;
            }

            $slugAKey = array_search($slugA['facet'], $facetSortList);
            $slugBKey = array_search($slugB['facet'], $facetSortList);
            return ($slugAKey < $slugBKey) ? -1 : 1;
        });
    }

    /**
     * @return string
     * @throws Mage_Core_Model_Store_Exception
     */
    protected function getBaseUrl()
    {
        if ($this->_baseUrl === null) {
            $categoryUrl = $this->getCurrentCategory()->getUrl();
            $queryPosition = strpos($categoryUrl, '?');
            $this->_baseUrl = ($queryPosition > 0) ? substr($categoryUrl, 0, $queryPosition) : $categoryUrl;
        }
        return rtrim($this->_baseUrl, '/');
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->_baseUrl = $baseUrl;
    }

    /**
     * @return Mage_Catalog_Model_Category
     * @throws Mage_Core_Model_Store_Exception
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
            try {
                if ($i % 2 === 0) {
                    $facetKey = $part;
                } else {
                    $facetValue = $this->getSlugAttributeMapper()->getAttributeValueBySlug($facetKey, $part);
                    if (!empty($facetKey)) {
                        $tweakwiseRequest->addFacetKey($facetKey, $facetValue);
                    }
                }
            } catch (Emico_TweakwiseExport_Model_Exception_SlugMappingException $exception) {
                continue;
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
     * @param bool $allowed
     */
    public function setIsAllowedInCurrentContext(bool $allowed)
    {
        $this->_isAllowedInCurrentContext = $allowed;
    }

    /**
     * The pathStrategy is only working correctly for standard category pages
     * Fallback to queryParam strategy for attribute landing pages, free search etc.
     *
     * @return bool
     * @throws Mage_Core_Model_Store_Exception
     */
    protected function isAllowedInCurrentContext()
    {
        if ($this->_isAllowedInCurrentContext !== null) {
            return $this->_isAllowedInCurrentContext;
        }
        $currentCategory = Mage::registry('current_category');
        return ($currentCategory !== null && $currentCategory->getId() !== Mage::app()->getStore()->getRootCategoryId());
    }

    /**
     * @param Emico_Tweakwise_Model_Catalog_Layer $state
     * @return mixed
     */
    public function buildCanonicalUrl(Emico_Tweakwise_Model_Catalog_Layer $state)
    {
        if (!$this->isAllowedInCurrentContext()) {
            return $this->_fallbackStrategy->buildCanonicalUrl($state);
        }

        $url = $this->getBaseUrl();

        // Add the attribute filters to the URL path
        $url .= '/' . $this->buildIndexableAttributePath($state);
    }

    /**
     * @param Emico_Tweakwise_Model_Catalog_Layer $state
     */
    protected function buildIndexableAttributePath(Emico_Tweakwise_Model_Catalog_Layer $state)
    {
        $slugs = [];
        $slugMapper = $this->getSlugAttributeMapper();
        $selectedFacets = $state->getSelectedFacets();

        return $this->getPathFromSlugs($slugs, $state);
    }
}