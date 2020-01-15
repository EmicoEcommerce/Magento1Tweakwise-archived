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
        if ($xmlElement->recommendation->count() === 1) {
            $this->setDataFromField($xmlElement->recommendation, 'items', 'item', self::ELEMENT_COUNT_NONE_OR_MORE);
        } elseif ($xmlElement->recommendation->count() > 1) {
            foreach ($xmlElement->recommendation as $recommendation) {
                $this->updateDataFromField($recommendation, 'items', 'item', self::ELEMENT_COUNT_NONE_OR_MORE);
            }
        } else {
            $this->setDataFromField($xmlElement, 'items', 'item', self::ELEMENT_COUNT_NONE_OR_MORE);
        }

        return $this;
    }

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
    protected function updateDataFromField(SimpleXMLElement $xmlElement, $field, $type, $count = self::ELEMENT_COUNT_ONE, $dataKey = null, $useProvidedAsData = false)
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
            // Get value if already present and update it.
            $value = $this->getData($dataKey);
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
}
