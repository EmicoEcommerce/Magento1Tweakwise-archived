<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Facet for filtering the layer
 *
 * @method Emico_Tweakwise_Model_Catalog_Layer_Filter setLayer(Emico_Tweakwise_Model_Catalog_Layer $layer);
 * @method Emico_Tweakwise_Model_Catalog_Layer getLayer();
 * @method Emico_Tweakwise_Model_Catalog_Layer_Filter setFacet(Emico_Tweakwise_Model_Bus_Type_Facet $facet);
 * @method Emico_Tweakwise_Model_Bus_Type_Facet getFacet();
 */
class Emico_Tweakwise_Model_Catalog_Layer_Filter extends Mage_Catalog_Model_Layer_Filter_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->getFacetSettings()->getTitle();
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Facet_Settings
     */
    public function getFacetSettings()
    {
        return $this->getFacet()->getFacetSettings();
    }

    /**
     * @return string|null
     */
    public function getLabel()
    {
        if (!$this->isActive()) {
            return null;
        }
        $value = $this->getValue();
        if (!is_array($value)) {
            $value = [$value];
        }

        if ($this->getFacet()->isPrice()) {
            /* @var $helper Mage_Core_Helper_Data */
            $helper = Mage::helper('core');
            foreach ($value as $k => $v) {
                $value[$k] = $helper->currency(round($v / 100), true, false);
            }
        }

        return implode($this->getFacet()->isSlider() ? '-' : ',', $value);
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        if ($this->getFacet()->isCategory()) {
            return count($this->getActiveAttributes()) > 0;
        } else {
            foreach ($this->getAttributes() as $attribute) {
                if ($attribute->getIsSelected()) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param null $attributes
     * @param int $level
     * @return Emico_Tweakwise_Model_Bus_Type_Attribute[]
     */
    public function getActiveAttributes($attributes = null, $level = 0)
    {
        $active = [];
        if ($attributes === null) {
            $attributes = $this->getAttributes();
        }
        foreach ($attributes as $attribute) {
            if (!$attribute->getIsSelected()) {
                continue;
            }

            if (!$this->getFacet()->isCategory() || $level > 0) {
                $active[] = $attribute;
            } else {
                if ($this->getFacetSettings()->getSelectionType() == 'tree') {
                    if (($children = $attribute->getChildren())) {
                        foreach ($this->getActiveAttributes($children, $level + 1) as $childAttribute) {
                            $active[] = $childAttribute;
                        }
                    }
                }
            }
        }

        return $active;
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Attribute[]
     */
    public function getAttributes()
    {
        return $this->getFacet()->getAttributes();
    }

    /**
     * @return array|null|string
     */
    public function getValue()
    {
        if ($this->getFacet()->isMultipleSelect() || $this->getFacet()->isSlider()) {
            $value = [];
            foreach ($this->getActiveAttributes() as $attribute) {
                $value[] = $attribute->getTitle();
            }

            return $value;
        } else {
            if (!$this->isActive()) {
                return null;
            } else {
                return $this->getActiveAttribute()->getTitle();
            }
        }
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Attribute|null
     */
    public function getActiveAttribute()
    {
        foreach ($this->getAttributes() as $attribute) {
            if ($attribute->getIsSelected()) {
                return $attribute;
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getRequestVar()
    {
        if ($this->getFacet()->isCategory()) {
            return 'cat';
        }

        return $this->getFacetSettings()->getUrlKey();
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Attribute[]
     */
    public function getActiveAttributesWithRemoveUrl()
    {
        $result = [];
        foreach ($this->getActiveAttributes() as $attribute) {
            $removeUrl = $this->getRemoveUrl($attribute);
            if ($removeUrl) {
                $result[$removeUrl] = $attribute;
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getAttributeCode()
    {
        return 'key-' . $this->getFacetSettings()->getUrlKey();
    }

    /**
     * @throws BadMethodCallException
     * @return array
     */
    public function getBounds()
    {
        if (!$this->getFacet()->isSlider()) {
            throw new BadMethodCallException('Facet is not a slider so it has no bounds.');
        }

        $value = [];
        foreach ($this->getAttributes() as $attribute) {
            if ($attribute->getIsSelected()) {
                continue;
            }
            $value[] = $attribute->getTitle();
        }

        return $value;
    }

    /**
     * @param Emico_Tweakwise_Model_Bus_Type_Attribute $parent
     * @param Emico_Tweakwise_Model_Bus_Type_Attribute $child
     * @return bool
     */
    protected function attributeIsRelated(Emico_Tweakwise_Model_Bus_Type_Attribute $parent, Emico_Tweakwise_Model_Bus_Type_Attribute $child)
    {
        if ($parent == $child) {
            return true;
        }
        foreach ($parent->getChildren() as $parentChild) {
            if ($this->attributeIsRelated($parentChild, $child)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Traversable $attributes
     * @return array
     */
    protected function getAttributeIds($attributes = null)
    {
        if ($attributes == null) {
            $attributes = $this->getAttributes();
        }
        $result = [];
        foreach ($attributes as $attribute) {
            $result[] = $attribute->getAttributeId();
            if (count($children = $attribute->getChildren()) == 0) {
                continue;
            }

            foreach ($this->getAttributeIds($children) as $childAttributeId) {
                $result[] = $childAttributeId;
            }
        }

        return $result;
    }
}

