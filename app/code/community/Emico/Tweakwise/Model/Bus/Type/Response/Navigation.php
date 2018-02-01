<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Model_Bus_Response_NavigationResponse
 *
 * @method Emico_Tweakwise_Model_Bus_Type_Facet[] getFacets();
 * @method Emico_Tweakwise_Model_Bus_Type_Item[] getItems();
 * @method Emico_Tweakwise_Model_Bus_Type_Redirect[]|null getRedirects();
 * @method Emico_Tweakwise_Model_Bus_Type_Properties getProperties();
 */
class Emico_Tweakwise_Model_Bus_Type_Response_Navigation extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * Set empty values
     */
    public function __construct()
    {
        parent::__construct();
        $this->setData('facets', []);
        $this->setData('items', []);
        $this->setData('properties', new Emico_Tweakwise_Model_Bus_Type_Properties());
    }

    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'facets', 'facet', self::ELEMENT_COUNT_NONE_OR_MORE);
        $this->setDataFromField($xmlElement, 'items', 'item', self::ELEMENT_COUNT_NONE_OR_MORE);
        $this->setDataFromField($xmlElement, 'redirects', 'redirect', self::ELEMENT_COUNT_NONE_OR_MORE);
        $this->setDataFromField($xmlElement, 'properties', 'properties');

        return $this;
    }
}