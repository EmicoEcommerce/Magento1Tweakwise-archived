<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Model_Bus_Response_NavigationResponse
 *
 * @method Emico_Tweakwise_Model_Bus_Type_MainSection[] getMainSections();
 */
class Emico_Tweakwise_Model_Bus_Type_Response_Bulknav extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'mainsection', 'mainSection', self::ELEMENT_COUNT_NONE_OR_MORE, 'main_sections', true);

        return $this;
    }
}