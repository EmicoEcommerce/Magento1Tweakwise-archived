<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * @method Emico_Tweakwise_Model_Bus_Type_Facet_Settings getFacetSettings();
 * @method Emico_Tweakwise_Model_Bus_Type_Attribute[] getAttributes();
 */
class Emico_Tweakwise_Model_Bus_Type_Facet extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'facetsettings', 'facet_settings', self::ELEMENT_COUNT_ONE, 'facet_settings');
        $this->setDataFromField($xmlElement, 'attributes', 'attribute', self::ELEMENT_COUNT_NONE_OR_MORE);

        return $this;
    }

    /**
     * @return bool
     */
    public function isTree()
    {
        return $this->getFacetSettings()->getSelectionType() == Emico_Tweakwise_Model_Bus_Type_Facet_Settings::SELECTION_TYPE_TREE;
    }

    /**
     *
     * Is it a color facet or not
     *
     * @return bool
     */
    public function isColor()
    {
        return $this->getFacetSettings()->getSelectionType() == Emico_Tweakwise_Model_Bus_Type_Facet_Settings::SELECTION_TYPE_COLOR;
    }

    /**
     * @return bool
     */
    public function isPrice()
    {
        return $this->getFacetSettings()->isPrice();
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Attribute[]
     */
    public function getActiveAttributes()
    {
        $attributes = [];
        foreach ($this->getAttributes() as $attribute) {
            if ($attribute->getIsSelected()) {
                $attributes[] = $attribute;
            }
        }

        return $attributes;
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Attribute
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
     * @return boolean
     */
    public function isActive()
    {
        $value = $this->getValue();
        if ($this->isMultipleSelect() || $this->isSlider()) {
            return count($value) > 0;
        }

        return $value !== null;
    }

    /**
     * @return array|null|string
     */
    public function getValue()
    {
        if ($this->isMultipleSelect() || $this->isSlider()) {
            $value = [];
            foreach ($this->getAttributes() as $attribute) {
                if (!$attribute->getIsSelected()) {
                    continue;
                }
                $value[] = $attribute->getTitle();
            }

            return $value;
        } else {
            foreach ($this->getAttributes() as $attribute) {
                if ($attribute->getIsSelected()) {
                    return $attribute->getTitle();
                }
            }

            return null;
        }
    }

    /**
     * @return bool
     */
    public function isMultipleSelect()
    {
        if ($this->isSlider()) {
            return false;
        }

        $settings = $this->getFacetSettings();
        if ($settings->getIsMultipleSelect()) {
            return true;
        }
        if ($this->isCategory()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isSlider()
    {
        return $this->getFacetSettings()->getSelectionType() == Emico_Tweakwise_Model_Bus_Type_Facet_Settings::SELECTION_TYPE_SLIDER;
    }

    /**
     * Returns null if unknown (happens when there are not attributes).
     *
     * @return bool|null
     */
    public function isCategory()
    {
        return $this->getFacetSettings()->getSource() == 'CATEGORY';
    }
}