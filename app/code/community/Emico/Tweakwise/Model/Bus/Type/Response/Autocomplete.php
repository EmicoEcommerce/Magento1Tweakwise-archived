<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Model_Bus_Response_NavigationResponse
 *
 * @method Emico_Tweakwise_Model_Bus_Type_Item[] getItems();
 * @method Emico_Tweakwise_Model_Bus_Type_Suggestion[] getSuggestions();
 * @method Emico_Tweakwise_Model_Bus_Type_InstantSearch getInstantSearch();
 */
class Emico_Tweakwise_Model_Bus_Type_Response_Autocomplete extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'items', 'item', self::ELEMENT_COUNT_NONE_OR_MORE);
        $this->setDataFromField($xmlElement, 'suggestions', 'suggestion', self::ELEMENT_COUNT_NONE_OR_MORE);
        $this->setDataFromField($xmlElement, 'instantsearch', 'instantSearch', self::ELEMENT_COUNT_ONE, 'instant_search');

        return $this;
    }
}