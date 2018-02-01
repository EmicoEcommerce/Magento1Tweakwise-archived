<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Block_Catalog_Layer_Facet_Category
 */
class Emico_Tweakwise_Block_Catalog_Layer_Facet_Category extends Emico_Tweakwise_Block_Catalog_Layer_Facet_Attribute
{
    /**
     * @var
     */
    protected $_activeCategories;

    /**
     * {@inheritDoc}
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('emico_tweakwise/catalog/layer/facet/attribute.phtml');
    }

    /**
     * {@inheritDoc}
     */
    public function getFacetUrl(Emico_Tweakwise_Model_Bus_Type_Attribute $attribute, $urlKey = null)
    {
        $helper = Mage::helper('emico_tweakwise');
        $query = Mage::app()->getRequest()->getQuery();
        $category = $helper->getFilterCategory($attribute->getAttributeId());
        $query['p'] = null;
        $query['ajax'] = null;

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

            $currentCategory = $this->getLayer()->getCurrentCategory();
            $categories = [];
            foreach ($categoryIds as $categoryId) {
                $category = $helper->getFilterCategory($categoryId);
                if (!$currentCategory || $currentCategory->getId() != $category->getId()) {
                    $categories[] = $category->getUrlKey();
                }
            }

            $query[$this->getUrlKey()] = implode('|', $categories);
            if (Mage::registry('current_category')) {
                $url = Mage::registry('current_category')->getUrl();
            } else {
                $params['_query'] = $query;

                return Mage::getUrl('*/*/*', $params);
            }
        }

        $query = array_filter($query);
        if ($query) {
            $url .= '?' . http_build_query($query);
        }

        return $url;
    }

    /**
     * @return string
     */
    public function getUrlKey()
    {
        return 'cat';
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        if ($this->_activeCategories === null) {
            $attributes = parent::getAttributes();
            $this->_activeCategories = [];

            foreach ($attributes as $attribute) {
                $this->_activeCategories[] = $attribute;
            }
        }

        return $this->_activeCategories;
    }
}
