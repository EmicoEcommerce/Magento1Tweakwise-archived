<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * @method string getTitle();
 * @method string getUrl();
 * @method int getRank();
 * @method Emico_Tweakwise_Model_Bus_Type_Subsection[] getSubsections();
 * @method Emico_Tweakwise_Model_Bus_Type_Banner[] getBanners();
 */
class Emico_Tweakwise_Model_Bus_Type_MainSection extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'title', self::DATA_TYPE_STRING);
        $this->setDataFromField($xmlElement, 'url', self::DATA_TYPE_STRING);
        $this->setDataFromField($xmlElement, 'rank', self::DATA_TYPE_INT);
        $this->setDataFromField($xmlElement, 'subsections', 'subsection', self::ELEMENT_COUNT_NONE_OR_MORE);
        $this->setDataFromField($xmlElement, 'banners', 'banner', self::ELEMENT_COUNT_NONE_OR_MORE);

        return $this;
    }
}