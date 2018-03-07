<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */
class Emico_Tweakwise_Model_UrlBuilder_Strategy_PathStrategy extends Emico_Tweakwise_Model_UrlBuilder_Strategy_AbstractStrategy implements
    Emico_Tweakwise_Model_UrlBuilder_Strategy_StrategyInterface,
    Emico_Tweakwise_Model_UrlBuilder_Strategy_RoutingStrategyInterface
{
    /**
     * Builds the URL for a facet attribute
     *
     * @param Emico_Tweakwise_Model_Catalog_Layer $state
     * @param Emico_Tweakwise_Model_Bus_Type_Attribute $attribute
     * @return null|string
     */
    public function buildUrl(Emico_Tweakwise_Model_Catalog_Layer $state, Emico_Tweakwise_Model_Bus_Type_Facet $facet, Emico_Tweakwise_Model_Bus_Type_Attribute $attribute)
    {
        $params = ['_current' => true, '_use_rewrite' => true, '_escape' => false];
        $query = ['ajax' => null];

        $query['p'] = null;

        $facetAttributes = [];
        foreach ($state->getSelectedFacets() as $selectedFacet) {
            foreach ($selectedFacet->getActiveAttributes() as $activeAttribute) {
                if ($attribute->getIsSelected() && $activeAttribute === $attribute) {
                    continue;
                }
                $facetAttributes[$selectedFacet->getFacetSettings()->getUrlKey()][] = $attribute;
            }
        }

        if (!$attribute->getIsSelected()) {
            $attributes[$facet->getFacetSettings()->getUrlKey()][] = $attribute;
        }

        $path = '';
        foreach ($facetAttributes as $facetKey => $attributes) {
            foreach ($attributes as $attribute) {
                $path .= '/' . $facetKey . '/';
            }
        }
        $path = strtolower($path);

        return Mage::getUrl('*/*/*', $params) . $path . '?ajax=1';
    }

    /**
     * @param Zend_Controller_Request_Http $request
     * @return Emico_Tweakwise_Model_Bus_Request_Navigation
     */
    public function decorateTweakwiseRequest(Zend_Controller_Request_Http $httpRequest, Emico_Tweakwise_Model_Bus_Request_Navigation $tweakwiseRequest)
    {
        $filterPath = trim($httpRequest->getParam('filter_path'), '/');
        if (empty($filterPath)) {
            return $tweakwiseRequest;
        }

        $filterParts = explode('/', $filterPath);

        foreach ($filterParts as $i => $part) {
            if ($i % 2 === 0) {
                $facetKey = $part;
            } else {
                $facetValue = $part;
                if (!empty($facet)) {
                    $tweakwiseRequest->addFacetKey($facetKey, $facetValue);
                }
            }
        }

        return $tweakwiseRequest;
    }

    /**
     * If you need to do custom
     *
     * @return bool
     */
    public function matchUrl(Zend_Controller_Request_Http $request)
    {
        $path = trim($request->getPathInfo(), '/');

        // See if a category rewrite exists for the current request
        $urlModel = Mage::getModel('core/url_rewrite');
        $urlModel->setStoreId(Mage::app()->getStore()->getId());
        $urlModel->loadByRequestPath(array_reverse($this->getPathsToCheck($path)));

        if ($urlModel->getId() === null) {
            return false;
        }

        $request->setParam('filter_path', substr($path, strlen($urlModel->getRequestPath())));
        $request->setRequestUri($request->getBaseUrl() . '/' . $urlModel->getTargetPath());
        $request->setPathInfo($urlModel->getTargetPath());

        return true;
    }

    /**
     * @param $fullUriPath
     * @return array
     */
    protected function getPathsToCheck($fullUriPath)
    {
        $path = explode('/', $fullUriPath);
        $paths = [];
        $lastPath = array_shift($path);
        $paths[] = $lastPath;
        foreach ($path as $i => $pathPart) {
            if ($i % 2 === 1) {
                continue;
            }

            $lastPath .= '/' . $pathPart;
            $paths[] = $lastPath;
        }
        return $paths;
    }
}