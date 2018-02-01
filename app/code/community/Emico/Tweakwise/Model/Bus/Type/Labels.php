<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * @method string GetLabel1();
 * @method string GetLabel2();
 * @method string GetLabel3();
 * @method string GetLabel4();
 * @method string GetLabel5();
 */
class Emico_Tweakwise_Model_Bus_Type_Labels extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'label1', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_NONE_OR_ONE);
        $this->setDataFromField($xmlElement, 'label2', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_NONE_OR_ONE);
        $this->setDataFromField($xmlElement, 'label3', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_NONE_OR_ONE);
        $this->setDataFromField($xmlElement, 'label4', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_NONE_OR_ONE);
        $this->setDataFromField($xmlElement, 'label5', self::DATA_TYPE_STRING, self::ELEMENT_COUNT_NONE_OR_ONE);

        return $this;
    }

    /**
     * @return string[]
     */
    public function getArray()
    {
        return [
            'label1' => $this->getLabel1(),
            'label2' => $this->getLabel2(),
            'label3' => $this->getLabel3(),
            'label4' => $this->getLabel4(),
            'label5' => $this->getLabel5(),
        ];
    }
}