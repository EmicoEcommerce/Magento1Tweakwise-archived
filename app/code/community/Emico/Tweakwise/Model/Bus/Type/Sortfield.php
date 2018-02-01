<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * @method string getTitle();
 * @method string getDisplayTitle();
 * @method string getOrder();
 * @method boolean getIsSelected();
 * @method string getUrl();
 */
class Emico_Tweakwise_Model_Bus_Type_Sortfield extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'title', self::DATA_TYPE_STRING);
        $this->setDataFromField($xmlElement, 'displaytitle', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_ONE, 'display_title');
        $this->setDataFromField($xmlElement, 'order', self::DATA_TYPE_STRING);
        $this->setDataFromField($xmlElement, 'isselected', self::DATA_TYPE_BOOLEAN, self::ELEMENT_COUNT_ONE, 'is_selected');
        $this->setDataFromField($xmlElement, 'url', self::DATA_TYPE_STRING);

        return $this;
    }
}