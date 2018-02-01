<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * @method int getNumberOfItems();
 * @method int getPageSize();
 * @method int getNumberOfPages();
 * @method int getCurrentPage();
 * @method int getSelectedCategoryId();
 * @method string getSearchTerm();
 * @method string getSuggestedSearchTerm();
 * @method boolean isDirectorySearch();
 * @method boolean isRootCategory();
 * @method string getPageUrl()
 * @method string getResetUrl()
 * @method Emico_Tweakwise_Model_Bus_Type_Sortfield[] getSortFields();
 */
class Emico_Tweakwise_Model_Bus_Type_Properties extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'nrofitems', self::DATA_TYPE_INT, self::ELEMENT_COUNT_ONE, 'number_of_items');
        $this->setDataFromField($xmlElement, 'pagesize', self::DATA_TYPE_INT, self::ELEMENT_COUNT_ONE, 'page_size');
        $this->setDataFromField($xmlElement, 'nrofpages', self::DATA_TYPE_INT, self::ELEMENT_COUNT_ONE, 'number_of_pages');
        $this->setDataFromField($xmlElement, 'currentpage', self::DATA_TYPE_INT, self::ELEMENT_COUNT_ONE, 'current_page');
        $this->setDataFromField($xmlElement, 'selectedcategory', self::DATA_TYPE_INT, self::ELEMENT_COUNT_ONE, 'selected_category_id');
        $this->setDataFromField($xmlElement, 'searchterm', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_NONE_OR_ONE, 'search_term');
        $this->setDataFromField($xmlElement, 'suggestedsearchterm', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_NONE_OR_ONE, 'suggested_search_term');
        $this->setDataFromField($xmlElement, 'isdirectsearch', self::DATA_TYPE_BOOLEAN, self::ELEMENT_COUNT_ONE, 'is_directory_search');
        $this->setDataFromField($xmlElement, 'isrootcategory', self::DATA_TYPE_BOOLEAN, self::ELEMENT_COUNT_ONE, 'is_root_category');
        $this->setDataFromField($xmlElement, 'pageurl', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_ONE, 'page_url');
        $this->setDataFromField($xmlElement, 'reseturl', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_ONE, 'reset_url');
        $this->setDataFromField($xmlElement, 'sortfields', 'sortfield', self::ELEMENT_COUNT_ONE_OR_MORE, 'sort_fields');

        return $this;
    }
}