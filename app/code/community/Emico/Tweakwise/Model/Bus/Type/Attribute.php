<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * @method string getTitle();
 * @method boolean getIsSelected();
 * @method int getNumberOfResults();
 * @method int getAttributeId();
 * @method string getUrl();
 * @method int getAlternateSortOrder();
 * @method Emico_Tweakwise_Model_Bus_Type_Attribute[] getChildren();
 */
class Emico_Tweakwise_Model_Bus_Type_Attribute extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'title', self::DATA_TYPE_STRING);
        $this->setDataFromField($xmlElement, 'isselected', self::DATA_TYPE_BOOLEAN, self::ELEMENT_COUNT_ONE, 'is_selected');
        $this->setDataFromField($xmlElement, 'nrofresults', self::DATA_TYPE_INT, self::ELEMENT_COUNT_ONE, 'number_of_results');
        $this->setDataFromField($xmlElement, 'attributeid', self::DATA_TYPE_INT, self::ELEMENT_COUNT_ONE, 'attribute_id');
        $this->setDataFromField($xmlElement, 'url', self::DATA_TYPE_STRING);
        $this->setDataFromField($xmlElement, 'children', 'attribute', self::ELEMENT_COUNT_NONE_OR_MORE);

        $this->setDataFromAttribute($xmlElement, 'alternatesortorder', self::DATA_TYPE_INT, 'alternate_sort_order');

        if ($parent = $xmlElement->xpath("parent::*")) {
            if ($parentLevel = $parent[0]->xpath("parent::*")) {
                if ($parentLevel[0]->isselected) {
                    $render = false;

                    foreach ($parent[0] as $_item) {
                        if ($_item->isselected && !$_item->childeren) {
                            $this->setData('render', 1);
                        }
                    }
                }
            }
        }

        return $this;
    }
}