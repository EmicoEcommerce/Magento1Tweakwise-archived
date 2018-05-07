<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Helper class for Emico_Tweakwise
 */
class Emico_Tweakwise_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Product attributes
     */
    const UPSELL_TEMPLATE_ATTRIBUTE = 'tweakwise_upsell_template';
    const RELATED_TEMPLATE_ATTRIBUTE = 'tweakwise_related_template';
    const FEATURED_TEMPLATE_ATTRIBUTE = 'tweakwise_featured_template';

    /**
     * @var Varien_Data_Collection
     */
    protected $_categoryCollection;

    /**
     * @param string $type
     * @param SimpleXMLElement $xmlElement
     * @return Emico_Tweakwise_Model_Bus_Type_Abstract
     * @throws Emico_Tweakwise_Model_Bus_Request_Exception
     */
    public function getTypeModel($type, SimpleXMLElement $xmlElement = null)
    {
        $model = Mage::getModel('emico_tweakwise/bus_type_' . $type);
        if ($model === false) {
            throw new Emico_Tweakwise_Model_Bus_Request_Exception('Could not find type for ' . $type);
        }

        if (!$model instanceof Emico_Tweakwise_Model_Bus_Type_Abstract) {
            throw new Emico_Tweakwise_Model_Bus_Request_Exception('Invalid type for ' . $type);
        }

        if ($xmlElement !== null) {
            $model->setDataFromXMLElement($xmlElement);
        }

        return $model;
    }

    /**
     * True if Tweakwise requests must be logged.
     *
     * @param Mage_Core_Model_Store|string|int|null $store
     * @return boolean
     */
    public function isLogEnabled($store = null)
    {
        return Mage::getStoreConfig('emico_tweakwise/global/log_enabled', $store);
    }

    /**
     * @param string $part
     * @param Mage_Core_Model_Store|string|int|null $store
     * @return boolean
     */
    public function isEnabled($part, $store = null)
    {
        if (!Mage::getStoreConfig('emico_tweakwise/global/key', $store)) {
            return false;
        }

        return Mage::getStoreConfig('emico_tweakwise/' . $part . '/enabled', $store);
    }

    /**
     * @param Mage_Core_Model_Store|string|int|null $store
     * @return boolean
     */
    public function hideFacetByOneOption($store = null)
    {
        return Mage::getStoreConfig('emico_tweakwise/navigation/hide_one_option_facet', $store);
    }

    /**
     * @param Mage_Core_Model_Store|string|int|null $store
     * @return boolean
     */
    public function isDirectDataEnabled($store = null)
    {
        return Mage::getStoreConfig('emico_tweakwise/directdata/enabled', $store);
    }

    /**
     * @param Mage_Core_Model_Store|string|int|null $store
     * @return bool
     */
    public function isRecommendationsEnabled($store = null)
    {
        return Mage::getStoreConfig('emico_tweakwise/recommendations/enabled', $store);
    }

    /**
     * @param Mage_Core_Model_Store|string|int|null $store
     * @return mixed
     */
    public function isRecommendationsAjax($store = null)
    {
        return Mage::getStoreConfigFlag('emico_tweakwise/recommendations/ajax', $store);
    }

    /**
     * @param Mage_Core_Model_Store|string|int|null $store
     * @return mixed
     */
    public function isNavigationAjax($store = null)
    {
        return Mage::getStoreConfig('emico_tweakwise/navigation/enabled_ajax', $store);
    }

    /**
     * True if category should be shown as link else show as filter query param
     *
     * @param int|string|Mage_Core_Model_Store $store
     * @return boolean
     */
    public function categoryAsLink($store = null)
    {
        $key = $this->searchSameAsCategory($store) || !$this->isSearchPage() ? 'category_as_link' : 'category_as_link_search';
        return Mage::getStoreConfig('emico_tweakwise/navigation/' . $key, $store);
    }

    /**
     * True if category link settings for search page should be the same as for catalog pages
     *
     * @param Mage_Core_Model_Store|string|int|null $store
     * @return boolean
     */
    public function searchSameAsCategory($store = null)
    {
        return Mage::getStoreConfig('emico_tweakwise/navigation/search_same_as_category', $store);
    }

    /**
     * @return bool
     */
    public function isSearchPage()
    {
        $request = Mage::app()->getRequest();
        return $request->getModuleName() == 'catalogsearch' && $request->getControllerName() == 'result';
    }

    /**
     * @param null $store
     *
     * @return mixed
     */
    public function stayInCategory($store = null)
    {
        return Mage::getStoreConfig('emico_tweakwise/autocomplete/in_category', $store);
    }

    /**
     * Get search templateId
     *
     * @param int|string|Mage_Core_Model_Store $store
     * @return string
     */
    public function getSearchTemplateId($store = null)
    {
        $template = Mage::getStoreConfig('emico_tweakwise/search/template', $store);

        return $template ? $template : null;
    }

    /**
     * The location of banners
     *
     * @param int|string|Mage_Core_Model_Store $store
     * @return string
     */
    public function getBannerLocation($store = null)
    {
        return Mage::getStoreConfig('emico_tweakwise/menu/banner_location', $store);
    }

    /**
     * Returns start depth for tree rendering.
     *
     * @param int|string|Mage_Core_Model_Store $store
     * @return int
     */
    public function getCategoryTreeStartDepth($store = null)
    {
        $key = $this->searchSameAsCategory($store) || !$this->isSearchPage() ? 'category_tree_start_depth' : 'category_tree_start_depth_search';
        return Mage::getStoreConfig('emico_tweakwise/navigation/' . $key, $store) + 1;
    }

    /**
     * Returns the number of levels to render from the deepest category.
     *
     * @param int|string|Mage_Core_Model_Store $store
     * @return int
     */
    public function getMaxTreeLevels($store = null)
    {
        $key = $this->searchSameAsCategory($store) || !$this->isSearchPage() ? 'max_tree_levels' : 'max_tree_levels_search';
        $maxTreeLevels = Mage::getStoreConfig('emico_tweakwise/navigation/' . $key, $store);
        return (int)($maxTreeLevels > 1) ? $maxTreeLevels + 1 : $maxTreeLevels;
    }

    /**
     * True if (images) color swatches must be used.
     *
     * @param int|string|Mage_Core_Model_Store $store
     * @return boolean
     */
    public function isImageSwatchActive($store = null)
    {
        return Mage::getStoreConfig('emico_tweakwise/navigation/magento_color_swatches', $store);
    }

    /**
     * Minimal chars to start autocomplete search
     *
     * @param int|string|Mage_Core_Model_Store $store
     * @return int
     */
    public function getMinSearchChars($store = null)
    {
        return (int) Mage::getStoreConfig('emico_tweakwise/autocomplete/min_search_chars', $store);
    }

    /**
     * Temporarily disable Tweakwise for this request
     */
    public function tempDisable()
    {
        Mage::app()->getStore()
            ->setConfig('emico_tweakwise/navigation/enabled', false)
            ->setConfig('emico_tweakwise/search/enabled', false)
            ->setConfig('emico_tweakwise/autocomplete/enabled', false)
            ->setConfig('emico_tweakwise/menu/enabled', false);
    }

    /**
     * @param int $categoryId
     * @return Mage_Catalog_Model_Category
     * @throws RuntimeException
     */
    public function getFilterCategory($categoryId)
    {
        $categoryId = Mage::helper('emico_tweakwiseexport')->fromStoreId($categoryId);

        if ($this->_categoryCollection == null) {
            $this->_categoryCollection = new Varien_Data_Collection();
        }

        if (!($category = $this->_categoryCollection->getItemById($categoryId))) {
            $category = Mage::getResourceModel('catalog/category_collection')
                ->addAttributeToSelect(['*'])
                ->addFieldToFilter('entity_id', ['eq' => $categoryId])
                ->getFirstItem();
            $this->_categoryCollection->addItem($category);
        }

        return $category;
    }

    /**
     * @param Mage_Catalog_Model_Category[] $categories
     */
    public function addToCategoryCollection(array $categories)
    {
        if (!$this->_categoryCollection) {
            $this->_categoryCollection = new Varien_Data_Collection();
        }
        foreach ($categories as $category) {
            if (!$category instanceof Mage_Catalog_Model_Category) {
                continue;
            }
            // Item could already exist
            if ($this->_categoryCollection->getItemById($category->getId())) {
                continue;
            }
            $this->_categoryCollection->addItem($category);
        }
    }

    /**
     * @param array $ids
     * @param Emico_Tweakwise_Model_Data_Collection $sortedCollection
     * @return Emico_Tweakwise_Model_Data_Collection
     */
    public function getProductCollection(array $ids, Emico_Tweakwise_Model_Data_Collection $sortedCollection)
    {
        if (count($ids) == 0) {
            return $sortedCollection;
        }
        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection
            ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->addTaxPercents()
            ->addUrlRewrite()
            ->addMinimalPrice()
            ->addFinalPrice();

        $collection->addIdFilter($ids);

        // Create new sorted collection
        if ($ids && is_array($ids)) {
            foreach (array_unique($ids) as $id) {
                if ($loadedProduct = $collection->getItemById($id)) {
                    $sortedCollection->addItem($loadedProduct);
                }
            }
        }

        return $sortedCollection;
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @return int|null
     */
    public function getProductUpsellRuleId(Mage_Catalog_Model_Product $product)
    {
        $ruleId = (int)$product->getData(Emico_Tweakwise_Helper_Data::UPSELL_TEMPLATE_ATTRIBUTE);
        if ($ruleId) {
            return $ruleId;
        }

        $ruleId = (int)Mage::getStoreConfig('emico_tweakwise/recommendations/product_upsell_template', $product->getStoreId());
        if ($ruleId) {
            return $ruleId;
        }

        return null;
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @return int|null
     */
    public function getProductRelatedRuleId(Mage_Catalog_Model_Product $product)
    {
        $ruleId = (int)$product->getData(Emico_Tweakwise_Helper_Data::RELATED_TEMPLATE_ATTRIBUTE);
        if ($ruleId) {
            return $ruleId;
        }

        $ruleId = (int)Mage::getStoreConfig('emico_tweakwise/recommendations/product_related_template', $product->getStoreId());
        if ($ruleId) {
            return $ruleId;
        }

        return null;
    }

    /**
     * @param Mage_Core_Block_Abstract $block
     * @return string
     */
    public function getRecommendationWrapperBlockId(Mage_Core_Block_Abstract $block)
    {
        return 'tweakwise-ajax-wrapper-' . md5($block->getNameInLayout());
    }

    /**
     * @param array $params
     * @return string
     */
    public function getRecommendationAjaxParamHash(array $params)
    {
        $key = (string)Mage::getConfig()->getNode('global/crypt/key');

        $params = array_intersect_key($params, [
            'handles' => true,
            'template' => true,
            'product' => true,
            'block' => true,
            'rule' => true,
        ]);

        ksort($params);
        return sha1(strtolower(http_build_query($params)) . $key);
    }
}