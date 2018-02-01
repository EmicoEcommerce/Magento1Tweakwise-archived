<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Model_Bus_Response_NavigationResponse
 *
 * @method Emico_Tweakwise_Model_Bus_Type_Item[] getItems();
 */
class Emico_Tweakwise_Model_Bus_Type_Response_Recommendations extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'items', 'item', self::ELEMENT_COUNT_NONE_OR_MORE);

        return $this;
    }
}