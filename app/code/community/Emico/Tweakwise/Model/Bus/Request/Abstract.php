<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Model_Bus_Request_Abstract
 */
abstract class Emico_Tweakwise_Model_Bus_Request_Abstract
{
    /**
     * @var Zend_Http_Client[]
     */
    protected $_clients = [];

    /**
     * @var array
     */
    protected $_parameters = [];

    /**
     * @var string
     */
    protected $_error;

    /**
     * @param string $parameter
     * @param string $value
     * @param string $separator
     * @return $this
     */
    public function addParameter($parameter, $value, $separator = '|')
    {
        if (isset($this->_parameters[$parameter])) {
            if ($value == null) {
                unset($this->_parameters[$parameter]);
            } else {
                $this->_parameters[$parameter] = $this->_parameters[$parameter] . $separator . $value;
            }
        } else {
            if ($value !== null) {
                $this->_parameters[$parameter] = (string)$value;
            }
        }

        return $this;
    }

    /**
     * @param string $parameter
     * @param string|null $value
     * @return $this
     */
    public function setParameter($parameter, $value)
    {
        if ($value === null) {
            unset($this->_parameters[$parameter]);
        } else {
            $value = strip_tags($value);
            $this->_parameters[$parameter] = (string)$value;
        }

        return $this;
    }

    /**
     * @param string $parameter
     * @return mixed|null
     */
    public function getParameter($parameter)
    {
        if (isset($this->_parameters[$parameter])) {
            return $this->_parameters[$parameter];
        }

        return null;
    }

    /**
     * @param string $parameter
     * @return bool
     */
    public function hasParameter($parameter)
    {
        return isset($this->_parameters[$parameter]);
    }

    /**
     * @return $this
     */
    public function reset()
    {
        $this->_parameters = [];

        return $this;
    }

    /**
     * @param Mage_Core_Model_Store|string|int|null $store
     * @return Emico_Tweakwise_Model_Bus_Type_Abstract
     * @throws Exception
     */
    public function execute($store = null)
    {
        /* @var $helper Emico_Tweakwise_Helper_Data */
        $helper = Mage::helper('emico_tweakwise');

        try {
            try {
                $httpResponse = $this->getClient($store)
                    ->resetParameters()
                    ->setParameterGet($this->getParameters())
                    ->request(Zend_Http_Client::GET);

                if (Mage::helper('emico_tweakwise')->isLogEnabled()) {
                    Mage::log($this->getClient()->getLastRequest(), null, 'tweakwise.log');
                }

                if ($httpResponse->getStatus() != 200) {
                    if ($helper->isLogEnabled()) {
                        Mage::log('Invalid response received by Tweakwise server, response code is not 200.', null,
                            'tweakwise.log');
                    }
                    throw new Emico_Tweakwise_Model_Bus_Request_Exception('Invalid response received by Tweakwise server, response code is not 200.');
                }

                $previous = libxml_use_internal_errors(true);
                $xmlElement = simplexml_load_string($httpResponse->getBody());
                if ($xmlElement === false) {
                    if ($helper->isLogEnabled()) {
                        Mage::log('Invalid response received by Tweakwise server, xml load fails.', null,
                            'tweakwise.log');
                    }
                    throw new Emico_Tweakwise_Model_Bus_Request_Exception('Invalid response received by Tweakwise server, xml load fails.');
                }
                libxml_use_internal_errors($previous);

                $result = $helper->getTypeModel($this->getResponseModel(), $xmlElement);
                $this->setError(null);
                return $result;
            } catch (Emico_Tweakwise_Model_Bus_Request_Exception $e) {
                Mage::log('Error during request: ' . $e->getMessage(), null, 'tweakwise.log');
                $this->setError($e->getMessage());
                return $helper->getTypeModel($this->getResponseModel());
            } catch (Zend_Http_Client_Exception $e) {
                Mage::log('Error during request: ' . $e->getMessage(), null, 'tweakwise.log');
                $this->setError($e->getMessage());
                return $helper->getTypeModel($this->getResponseModel());
            }
        } catch (Exception $e) {
            $helper->tempDisable();
            throw $e;
        }
    }

    /**
     * @param Mage_Core_Model_Store|string|int|null $store
     * @throws Emico_Tweakwise_Model_Bus_Request_Exception
     * @return Zend_Http_Client
     */
    protected function getClient($store = null)
    {
        $store = $this->getStore($store);

        if (!isset($this->_clients[$store->getId()])) {
            $this->_clients[$store->getId()] = new Zend_Http_Client(null, ['timeout' => 20]);
            $this->setClientUrl();
        }

        return $this->_clients[$store->getId()];
    }

    /**
     * @param Mage_Core_Model_Store|string|int|null
     * @return Mage_Core_Model_Store
     */
    protected function getStore($store)
    {
        $app = Mage::app();
        if ($store === null) {
            if ($app->getStore()->isAdmin()) {
                $store = $app->getRequest()->getParam('store');
            }
        }

        return Mage::app()->getStore($store);
    }

    /**
     * @param string $pattern
     * @param array $urlParts
     * @param Mage_Core_Model_Store|string|int|null $store
     * @return $this
     * @throws Emico_Tweakwise_Model_Bus_Request_Exception
     */
    protected function setClientUrl($pattern = '{baseUrl}/{service}/{key}', array $urlParts = [], $store = null)
    {
        $store = $this->getStore($store);

        if (!isset($urlParts['baseUrl'])) {
            $urlParts['baseUrl'] = Mage::getStoreConfig('emico_tweakwise/global/server_url', $store);
        }
        $urlParts['baseUrl'] = rtrim($urlParts['baseUrl'], '/');

        if (!isset($urlParts['service'])) {
            $urlParts['service'] = $this->getServiceKey();
        }
        $urlParts['service'] = trim($urlParts['service'], '/');

        if (!isset($urlParts['key'])) {
            $urlParts['key'] = Mage::getStoreConfig('emico_tweakwise/global/key', $store);;
        }

        if (empty($urlParts['key'])) {
            throw new Emico_Tweakwise_Model_Bus_Request_Exception('Please provide a valid tweakwise key in System -> Configuration -> Tweakwise -> Key');
        } else {
            if (!preg_match('/[a-z0-9]{6}/i', $urlParts['key'])) {
                throw new Emico_Tweakwise_Model_Bus_Request_Exception('Please provide a valid tweakwise key in System -> Configuration -> Tweakwise -> Key');
            }
        }

        $url = $pattern;
        foreach ($urlParts as $key => $value) {
            $url = str_replace('{' . $key . '}', $value, $url);
        }

        try {
            $this->getClient($store)->setUri($url);
        } catch (Zend_Http_Client_Exception $e) {
            throw new Emico_Tweakwise_Model_Bus_Request_Exception('Invalid uri provided in in System -> Configuration -> Tweakwise -> Server url', 0, $e);
        } catch (Zend_Uri_Exception $e) {
            throw new Emico_Tweakwise_Model_Bus_Request_Exception('Invalid uri provided in in System -> Configuration -> Tweakwise -> Server url', 0, $e);
        }

        return $this;
    }

    /**
     * @return string
     */
    protected abstract function getServiceKey();

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->_parameters;
    }

    /**
     * @return string
     */
    protected function getResponseModel()
    {
        return 'response_' . $this->getServiceKey();
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->_error;
    }

    /**
     * @param string $error
     * @return $this
     */
    protected function setError($error)
    {
        $this->_error = (string)$error;
        return $this;
    }
}