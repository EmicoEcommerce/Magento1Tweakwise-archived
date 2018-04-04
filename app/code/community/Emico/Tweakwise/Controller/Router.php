<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */
class Emico_Tweakwise_Controller_Router extends Mage_Core_Controller_Varien_Router_Standard
{
    /**
     * Match the request
     *
     * @param Zend_Controller_Request_Http $request
     * @return boolean
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        if (!$this->_beforeModuleMatch()) return false;

        $helper = Mage::helper('emico_tweakwise/uriStrategy');
        $strategies = $helper->getActiveStrategies();

        foreach ($strategies as $strategy) {
            if (!$strategy instanceof Emico_Tweakwise_Model_UrlBuilder_Strategy_RoutingStrategyInterface) {
                continue;
            }

            $result = $strategy->matchUrl($request);
            if ($result) {
                return $result;
            }
        }

        return false;
    }
}