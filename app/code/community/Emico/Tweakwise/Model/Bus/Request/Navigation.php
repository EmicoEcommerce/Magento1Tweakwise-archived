<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Model_Bus_Request_Navigation
 */
class Emico_Tweakwise_Model_Bus_Request_Navigation extends Emico_Tweakwise_Model_Bus_Request_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function execute($store = null)
    {
        parent::getClient()->setUri(
            rtrim(Mage::getStoreConfig('emico_tweakwise/global/server_url', $store), '/') . '/' .
            ($this->hasParameter('tn_q') ? 'navigation-search' : 'navigation') .
            '/' . Mage::getStoreConfig('emico_tweakwise/global/key', $store)
        );

        return parent::execute($store);
    }

    /**
     * @param Mage_Catalog_Model_Category|int $category
     * @return $this
     */
    public function addCategory($category)
    {
        if ($category instanceof Mage_Catalog_Model_Category) {
            $store = $category->getStore();
            $category = $category->getId();
        } else {
            $store = Mage::app()->getStore();
        }

        $helper = Mage::helper('emico_tweakwiseexport');
        $this->addParameter('tn_cid', $helper->toStoreId($store, $category), '-');

        return $this;
    }

    /**
     * @return bool
     */
    public function hasCategoryParam()
    {
        return $this->hasParameter('tn_cid');
    }

    /**
     * @param int $facetId
     * @param string|null $facetValue
     * @param bool $replace
     * @return $this
     */
    public function addFacetId($facetId, $facetValue, $replace = false)
    {
        $facetId = 'tn_f' . ((int)$facetId);
        $facetValue = (string)$facetValue;
        if ($replace) {
            $this->setParameter($facetId, $facetValue);
        } else {
            $this->addParameter($facetId, $facetValue);
        }

        return $this;
    }

    /**
     * @param string $facetKey
     * @param string|null $facetValue
     * @param bool $replace
     * @return $this
     */
    public function addFacetKey($facetKey, $facetValue, $replace = false)
    {
        if (is_array($facetValue)) {
            foreach ($facetValue as $value) {
                $this->addFacetKey($facetKey, $value, false);
            }

            return $this;
        }

        $facetKey = 'tn_fk_' . $facetKey;
        $facetValue = (string)$facetValue;
        if ($replace) {
            $this->setParameter($facetKey, $facetValue);
        } else {
            $this->addParameter($facetKey, $facetValue);
        }

        return $this;
    }

    /**
     * @param string|null $direction
     */
    public function setSort($direction)
    {
        $this->setParameter('tn_sort', $direction);
    }

    /**
     * @param int|null $page
     */
    public function setPage($page)
    {
        $this->setParameter('tn_p', $page);
    }

    /**
     * @param int|null $products
     */
    public function setProductsPerPage($products)
    {
        if ($products === 'all') {
            $products = 1000;
        }
        $this->setParameter('tn_ps', $products);
    }

    /**
     * @return int|null
     */
    public function getProductsPerPage()
    {
        return $this->getParameter('tn_ps');
    }

    /**
     * @param string|null $templateId
     */
    public function setTemplateId($templateId)
    {
        $this->setFilterTemplateId($templateId);
    }

    /**
     * @param $templateId
     */
    public function setFilterTemplateId($templateId)
    {
        $this->setParameter('tn_ft', $templateId);
    }

    /**
     * @param $templateId
     */
    public function setSortTemplateId($templateId)
    {
        $this->setParameter('tn_st', $templateId);
    }

    /**
     * @param string|null $query
     */
    public function setSearchQuery($query)
    {
        $this->setParameter('tn_q', $query);
    }

    /**
     * {@inheritDoc}
     */
    protected function getServiceKey()
    {
        return 'navigation';
    }
}