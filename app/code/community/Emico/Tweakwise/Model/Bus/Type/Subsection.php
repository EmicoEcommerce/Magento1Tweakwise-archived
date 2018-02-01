<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * @method string getTitle();
 * @method int getRank();
 * @method int getColumn();
 * @method Emico_Tweakwise_Model_Bus_Type_NavItem[] getNavItems();
 */
class Emico_Tweakwise_Model_Bus_Type_Subsection extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'title', self::DATA_TYPE_STRING);
        $this->setDataFromField($xmlElement, 'url', self::DATA_TYPE_STRING);
        $this->setDataFromField($xmlElement, 'column', self::DATA_TYPE_INT);
        $this->setDataFromField($xmlElement, 'navitems', 'navItem', self::ELEMENT_COUNT_NONE_OR_MORE, 'nav_items');

        return $this;
    }
}