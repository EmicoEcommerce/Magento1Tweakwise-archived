<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * @method string getTitle();
 * @method string getType();
 * @method string getUrl();
 */
class Emico_Tweakwise_Model_Bus_Type_Banner extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'title', self::DATA_TYPE_STRING);
        $this->setDataFromField($xmlElement, 'type', self::DATA_TYPE_STRING);
        $this->setDataFromField($xmlElement, 'url', self::DATA_TYPE_STRING);

        return $this;
    }
}