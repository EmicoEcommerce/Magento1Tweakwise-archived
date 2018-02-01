<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * @method string getTitle();
 */
class Emico_Tweakwise_Model_Bus_Type_Suggestion extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'title', self::DATA_TYPE_STRING);

        return $this;
    }
}