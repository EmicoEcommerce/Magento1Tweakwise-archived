<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * @method string getTitle();
 * @method int getRank();
 * @method string getUrl();
 */
class Emico_Tweakwise_Model_Bus_Type_NavItem extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'title', self::DATA_TYPE_STRING);
        $this->setDataFromField($xmlElement, 'rank', self::DATA_TYPE_INT);
        $this->setDataFromField($xmlElement, 'url', self::DATA_TYPE_STRING);

        return $this;
    }
}