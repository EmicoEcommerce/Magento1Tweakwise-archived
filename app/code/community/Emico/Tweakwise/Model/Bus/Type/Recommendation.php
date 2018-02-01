<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * @method Emico_Tweakwise_Model_Bus_Type_Item[] getItems();
 * @method int getId();
 * @method string getName();
 */
class Emico_Tweakwise_Model_Bus_Type_Recommendation extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'items', 'item', self::ELEMENT_COUNT_NONE_OR_MORE);
        $this->setDataFromField($xmlElement, 'id', self::DATA_TYPE_INT, self::ELEMENT_COUNT_ONE);
        $this->setDataFromField($xmlElement, 'name', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_ONE);

        return $this;
    }
}