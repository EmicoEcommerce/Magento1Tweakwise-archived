<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Block_Catalog_Layer_Facet_Attribute
 *
 * @method mixed getCleanValue()
 */
class Emico_Tweakwise_Block_Catalog_Layer_Facet_Attribute extends Mage_Core_Block_Template
{
    /**
     * @var Emico_Tweakwise_Model_Bus_Type_Facet
     */
    protected $_facet;

    /**
     * {@inheritDoc}
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('emico_tweakwise/catalog/layer/facet/attribute.phtml');
    }

    /**
     * @return Emico_Tweakwise_Model_Catalog_Layer
     */
    public function getLayer()
    {
        return Mage::getSingleton('emico_tweakwise/catalog_layer');
    }

    /**
     * @return boolean
     */
    public function isVisible()
    {
        return $this->getFacetSettings()->getIsVisible() && count($this->getAttributes()) > 0;
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Facet_Settings
     */
    public function getFacetSettings()
    {
        return $this->getFacet()->getFacetSettings();
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Facet
     */
    public function getFacet()
    {
        return $this->_facet;
    }

    /**
     * @param Emico_Tweakwise_Model_Bus_Type_Facet $facet
     * @return Emico_Tweakwise_Block_Catalog_Layer_Facet_Attribute
     */
    public function setFacet(Emico_Tweakwise_Model_Bus_Type_Facet $facet)
    {
        $this->_facet = $facet;

        return $this;
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Attribute[]
     */
    public function getAttributes()
    {
        return $this->getFacet()->getAttributes();
    }

    /**
     * @return boolean
     */
    public function showMoreText()
    {
        return \count($this->getAttributes()) > $this->getFacetSettings()->getNumberOfShownAttributes();
    }

    /**
     * @param Emico_Tweakwise_Model_Bus_Type_Attribute $item
     * @return bool
     */
    public function isMoreItem(Emico_Tweakwise_Model_Bus_Type_Attribute $item)
    {
        $items = $this->getAttributes();
        $key = array_search($item, $items);

        return $key >= $this->getFacetSettings()->getNumberOfShownAttributes();
    }

    /**
     * @return bool
     */
    public function hasAlternateSort()
    {
        $filter = function (Emico_Tweakwise_Model_Bus_Type_Attribute $item) {
            return is_numeric($item->getAlternateSortOrder());
        };

        $itemsWithAlternateOrder = array_filter($this->getAttributes(), $filter);
        return \count($this->getAttributes()) === \count($itemsWithAlternateOrder);
    }

    /**
     * Get magento url based on current url
     *
     * @param Emico_Tweakwise_Model_Bus_Type_Attribute|null $attribute
     * @return string
     */
    public function getFacetUrl(Emico_Tweakwise_Model_Bus_Type_Attribute $attribute)
    {
        return Mage::getModel('emico_tweakwise/urlBuilder_urlBuilder')->buildUrl($this->getFacet(), $attribute);
    }

    /**
     * @return string
     */
    public function getUrlKey()
    {
        return $this->getFacetSettings()->getUrlKey();
    }

    /**
     * @return bool
     */
    public function isCheckbox()
    {
        return $this->getFacetSettings()->getSelectionType() == Emico_Tweakwise_Model_Bus_Type_Facet_Settings::SELECTION_TYPE_CHECKBOX;
    }

    /**
     * @param Emico_Tweakwise_Model_Bus_Type_Attribute $item
     * @return string
     */
    public function getItemTitle(Emico_Tweakwise_Model_Bus_Type_Attribute $item)
    {
        $facet = $this->getFacet();
        $settings = $this->getFacetSettings();
        if ($this->getFacet()->isSlider()) {
            $values = $facet->getValue();
            if ($settings->isPrice()) {
                $helper = Mage::helper('core');
                foreach ($values as &$value) {
                    $value = $helper->formatPrice($value, false);
                }
                $facet->getValue();
            }
            $title = join(' - ', $values);
        } else {
            $title = $item->getTitle();
        }

        return Zend_Filter::filterStatic($settings->getPrefix() . ' ' . $title . ' ' . $settings->getPostfix(), 'StringTrim');
    }

    /**
     * @return bool
     */
    protected function hasSelectedOption()
    {
        return count($this->getSelectedOptions()) > 0;
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Attribute[]
     */
    protected function getSelectedOptions()
    {
        $attributes = $this->getFacet()->getAttributes();
        $selectedFilter = function (Emico_Tweakwise_Model_Bus_Type_Attribute $attribute)
        {
            return $attribute->getIsSelected();
        };

        return array_filter($attributes, $selectedFilter);
    }

    /**
     * @return string
     */
    public function getHrefAttributes()
    {
        $attributes = [];
        if (Mage::helper('emico_tweakwise/seo')->shouldApplyNoIndexNoFollow($this->getFacet())) {
            $attributes['rel'] = 'nofollow';
        }

        return implode(' ',
            array_map(
                function ($key, $val) {
                    return sprintf('%s="%s"', $key, $val);
                },
                array_keys($attributes), array_values($attributes)
            )
        );
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        $helper = Mage::helper('emico_tweakwise');

        if (!$this->getFacetSettings()->getIsVisible() ||
            ($helper->hideFacetByOneOption() && count($this->getAttributes()) <= 1 && !$this->hasSelectedOption()))
        {
            return '';
        }

        return parent::_toHtml();
    }
}
