<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * @method int getFacetId();
 * @method boolean getIsVisible();
 * @method string getUrlKey();
 * @method string getTitle();
 * @method boolean getIsCollapsible();
 * @method boolean getIsCollapsed();
 * @method int getNumberOfShownAttributes();
 * @method string getExpandText();
 * @method string getCollapseText();
 * @method boolean getIsMultipleSelect();
 * @method int getMultiSelectLogic();
 * @method string getSelectionType();
 * @method int getNumberOfColumns();
 * @method boolean getIsNumberOfResultVisible();
 * @method boolean getIsInfoVisible();
 * @method string getInfoText();
 * @method int getSource();
 * @method int getPrefix();
 * @method int getPostfix();
 */
class Emico_Tweakwise_Model_Bus_Type_Facet_Settings extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * Possible multi select logic options
     */
    const MULTI_SELECT_LOGIC_AND = 'AND';
    const MULTI_SELECT_LOGIC_OR = 'OR';

    /**
     * Possible facet sources
     */
    const FACET_SOURCE_CATEGORY = 'CATEGORY';
    const FACET_SOURCE_FEED = 'FEED';
    const FACET_SOURCE_DERIVATIVE = 'AFGELEID';

    /**
     * Possible selection types
     */
    const SELECTION_TYPE_CHECKBOX = 'checkbox';
    const SELECTION_TYPE_LINK = 'link';
    const SELECTION_TYPE_SLIDER = 'slider';
    const SELECTION_TYPE_TREE = 'tree';
    const SELECTION_TYPE_COLOR = 'color';

    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'facetid', self::DATA_TYPE_INT, self::ELEMENT_COUNT_ONE, 'facet_id');
        $this->setDataFromField($xmlElement, 'isvisible', self::DATA_TYPE_BOOLEAN, self::ELEMENT_COUNT_ONE, 'is_visible');
        $this->setDataFromField($xmlElement, 'urlkey', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_ONE, 'url_key');
        $this->setDataFromField($xmlElement, 'title', self::DATA_TYPE_STRING);
        $this->setDataFromField($xmlElement, 'iscollapsible', self::DATA_TYPE_BOOLEAN, self::ELEMENT_COUNT_ONE, 'is_collapsible');
        $this->setDataFromField($xmlElement, 'iscollapsed', self::DATA_TYPE_BOOLEAN, self::ELEMENT_COUNT_ONE, 'is_collapsed');
        $this->setDataFromField($xmlElement, 'nrofshownattributes', self::DATA_TYPE_INT, self::ELEMENT_COUNT_ONE, 'number_of_shown_attributes');
        $this->setDataFromField($xmlElement, 'expandtext', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_ONE, 'expand_text');
        $this->setDataFromField($xmlElement, 'collapsetext', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_ONE, 'collapse_text');
        $this->setDataFromField($xmlElement, 'ismultiselect', self::DATA_TYPE_BOOLEAN, self::ELEMENT_COUNT_ONE, 'is_multiple_select');
        $this->setDataFromField($xmlElement, 'multiselectlogic', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_ONE, 'multi_select_logic');
        $this->setDataFromField($xmlElement, 'selectiontype', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_ONE, 'selection_type');
        $this->setDataFromField($xmlElement, 'nrofcolumns', self::DATA_TYPE_INT, self::ELEMENT_COUNT_ONE, 'number_of_columns');
        $this->setDataFromField($xmlElement, 'isnrofresultsvisible', self::DATA_TYPE_BOOLEAN, self::ELEMENT_COUNT_ONE, 'is_number_of_result_visible');
        $this->setDataFromField($xmlElement, 'isinfovisible', self::DATA_TYPE_BOOLEAN, self::ELEMENT_COUNT_ONE, 'is_info_visible');
        $this->setDataFromField($xmlElement, 'infotext', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_NONE_OR_ONE, 'info_text');
        $this->setDataFromField($xmlElement, 'source', self::DATA_TYPE_STRING);
        $this->setDataFromField($xmlElement, 'prefix', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_NONE_OR_ONE);
        $this->setDataFromField($xmlElement, 'postfix', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_NONE_OR_ONE);

        return $this;
    }

    /**
     * @return bool
     */
    public function isPrice()
    {
        return $this->getUrlKey() == 'price';
    }
}