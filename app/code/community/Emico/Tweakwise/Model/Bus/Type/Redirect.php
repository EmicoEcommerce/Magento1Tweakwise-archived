<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * @method string getUrl();
 */
class Emico_Tweakwise_Model_Bus_Type_Redirect extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'url', self::DATA_TYPE_STRING);

        return $this;
    }
}