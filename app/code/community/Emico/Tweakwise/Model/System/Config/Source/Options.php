<?php

/**
 * @copyright (c) Emico 2014
 */
abstract class Emico_Tweakwise_Model_System_Config_Source_Options extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    /**
     * @var bool
     */
    protected $_allowEmpty = true;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getAllOptions();
    }

    /**
     * Retrieve All options
     * @return array
     * @throws Emico_Tweakwise_Model_Exception
     */
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = [];
            if ($this->_allowEmpty) {
                $this->_options[null] = '';
            }

            if (!Mage::getStoreConfig('emico_tweakwise/global/key')) {
                return $this->_options;
            }

            $request = Mage::getModel('emico_tweakwise/bus_request_' . $this->getRequestModel());
            if (!$request instanceof Emico_Tweakwise_Model_Bus_Request_Abstract) {
                throw new Emico_Tweakwise_Model_Exception('Option did not return valid class');
            }

            try {
                /** @var Emico_Tweakwise_Model_Bus_Type_Response_Template $result */
                $response = $request->execute();
            } catch (Exception $e) {
                if (Mage::helper('emico_tweakwise')->isLogEnabled()) {
                    Mage::log($e->getMessage(), null, 'tweakwise.log');
                }

                Mage::logException($e);
                return $this->_options;
            }

            foreach ($this->parseResult($response) as $key => $value) {
                $this->_options[$key] = $value;
            }

            uasort($this->_options, 'strnatcasecmp');
        }

        return $this->_options;
    }

    /**
     * @return string
     */
    protected abstract function getRequestModel();

    /**
     * @param Emico_Tweakwise_Model_Bus_Type_Abstract $response
     * @return array
     */
    protected abstract function parseResult(Emico_Tweakwise_Model_Bus_Type_Abstract $response);
}