<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Model_Bus_Type_Abstract
 */
abstract class Emico_Tweakwise_Model_Bus_Type_Abstract extends Varien_Object
{
    /**
     * Possible scalar data types for child elements
     */
    const DATA_TYPE_INT = 'int';
    const DATA_TYPE_FLOAT = 'float';
    const DATA_TYPE_BOOLEAN = 'boolean';
    const DATA_TYPE_STRING = 'string';

    /**
     * Possible times the xml element can occour
     */
    const ELEMENT_COUNT_ONE = '1';
    const ELEMENT_COUNT_NONE_OR_ONE = '0..1';
    const ELEMENT_COUNT_ONE_OR_MORE = '1..n';
    const ELEMENT_COUNT_NONE_OR_MORE = '0..n';

    /**
     * @param SimpleXMLElement $xmlElement
     * @return $this
     */
    public abstract function setDataFromXMLElement(SimpleXMLElement $xmlElement);

    /**
     * @param SimpleXMLElement $xmlElement
     * @param string $field
     * @param string $type
     * @param string $count
     * @param string $dataKey
     * @param bool $useProvidedAsData
     * @throws Emico_Tweakwise_Model_Bus_Type_Exception
     * @return $this
     */
    protected function setDataFromField(SimpleXMLElement $xmlElement, $field, $type, $count = self::ELEMENT_COUNT_ONE, $dataKey = null, $useProvidedAsData = false)
    {
        if ($dataKey === null) {
            $dataKey = $field;
        }

        /** @var $data SimpleXMLElement */
        $data = $useProvidedAsData ? $xmlElement : $xmlElement->{$field};

        if (!is_object($data)) {
            $this->setData($dataKey, '');

            return $this;
        }

        if ($data->count() == 0 && ($count === self::ELEMENT_COUNT_ONE_OR_MORE || $count === self::ELEMENT_COUNT_ONE)) {
            throw new Emico_Tweakwise_Model_Bus_Type_Exception('Found no elements for field "' . $field . '" but at least one is required');
        }

        if ($count == self::ELEMENT_COUNT_ONE_OR_MORE || $count == self::ELEMENT_COUNT_NONE_OR_MORE) {
            $value = [];
            foreach ($data->children() as $child) {
                $value[] = $this->elementToType($child, $type);
            }

            if ($count == self::ELEMENT_COUNT_ONE_OR_MORE && count($value) == 0) {
                throw new Emico_Tweakwise_Model_Bus_Type_Exception('Found no elements for field "' . $field . '" but at least one is required');
            }
        } else {
            $value = $this->elementToType($data, $type);
        }
        $this->setData($dataKey, $value);

        return $this;
    }

    /**
     * @param SimpleXMLElement $xmlElement
     * @param string $type
     * @return bool|Emico_Tweakwise_Model_Bus_Type_Abstract
     */
    protected function elementToType(SimpleXMLElement $xmlElement, $type)
    {
        switch ($type) {
            case self::DATA_TYPE_INT:
                return (int)(string)$xmlElement;
            case self::DATA_TYPE_FLOAT:
                return (float)(string)$xmlElement;
            case self::DATA_TYPE_BOOLEAN:
                return strtolower((string)$xmlElement) == 'true';
            case self::DATA_TYPE_STRING:
                return (string)$xmlElement;
            default:
                /* @var $helper Emico_Tweakwise_Helper_Data */
                $helper = Mage::helper('emico_tweakwise');

                return $helper->getTypeModel($type, $xmlElement);
        }
    }
}