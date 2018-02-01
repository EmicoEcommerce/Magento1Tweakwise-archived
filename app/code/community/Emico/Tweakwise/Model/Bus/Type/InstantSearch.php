<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * @method bool getIsActive();
 * @method string getSearchTerm();
 */
class Emico_Tweakwise_Model_Bus_Type_InstantSearch extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'isactive', self::DATA_TYPE_BOOLEAN, self::ELEMENT_COUNT_ONE, 'is_active');
        $this->setDataFromField($xmlElement, 'searchterm', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_NONE_OR_ONE, 'search_term');

        return $this;
    }
}