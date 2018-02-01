<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * @method string getId();
 * @method string getTitle();
 * @method int getPrice();
 * @method string getBrand();
 * @method string getImage();
 * @method string getUrl();
 * @method Emico_Tweakwise_Model_Bus_Type_Labels getLabels();
 */
class Emico_Tweakwise_Model_Bus_Type_Item extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'itemno', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_ONE, 'id');
        $this->setDataFromField($xmlElement, 'title', self::DATA_TYPE_STRING);
        $this->setDataFromField($xmlElement, 'price', self::DATA_TYPE_INT);
        $this->setDataFromField($xmlElement, 'brand', self::DATA_TYPE_STRING);
        $this->setDataFromField($xmlElement, 'image', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_NONE_OR_ONE);
        $this->setDataFromField($xmlElement, 'url', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_NONE_OR_ONE);
        $this->setDataFromField($xmlElement, 'labels', 'labels', self::ELEMENT_COUNT_NONE_OR_ONE);

        return $this;
    }
}