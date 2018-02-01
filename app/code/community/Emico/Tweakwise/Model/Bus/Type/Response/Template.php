<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Model_Bus_Response_NavigationResponse
 *
 * @method Emico_Tweakwise_Model_Bus_Type_Template[] getTemplates();
 */
class Emico_Tweakwise_Model_Bus_Type_Response_Template extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'template', 'template', self::ELEMENT_COUNT_NONE_OR_MORE, 'templates', true);

        return $this;
    }
}