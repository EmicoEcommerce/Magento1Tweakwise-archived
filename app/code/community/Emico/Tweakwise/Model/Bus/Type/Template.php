<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * @method string getName();
 * @method int getTemplateId();
 */
class Emico_Tweakwise_Model_Bus_Type_Template extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'name', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_ONE, 'name');
        $this->setDataFromField($xmlElement, 'templateid', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_ONE, 'template_id');

        return $this;
    }
}