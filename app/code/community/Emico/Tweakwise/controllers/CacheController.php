<?php

/**
 * Export feed controller
 */
class Emico_Tweakwise_CacheController extends Mage_Core_Controller_Front_Action
{
    /**
     * Clear all magento cache with the tweakwise auth key as authentication
     */
    public function clearAction()
    {
        if (Mage::getStoreConfig('emico_tweakwise/global/key') != $this->getRequest()->get('key')) {
            $this->norouteAction();

            return;
        }

        $transport = new Varien_Object(['flush' => true]);
        Mage::dispatchEvent('tweakwise_clear_cache', [
            'request' => $this->getRequest(),
            'transport' => $transport,
        ]);

        if ($transport->getData('flush')) {
            Mage::helper('emico_tweakwiseexport')->log('Cache cleared');
            Mage::app()->getCacheInstance()->flush();
            Mage::app()->getCacheInstance()->cleanType('config');
            Mage::app()->getCacheInstance()->cleanType('layout');
            Mage::app()->getCacheInstance()->cleanType('block_html');
            Mage::app()->getCacheInstance()->cleanType('translate');
            Mage::app()->getCacheInstance()->cleanType('collections');
            Mage::app()->getCacheInstance()->cleanType('eav');
            Mage::app()->getCacheInstance()->cleanType('config_api');

            if (class_exists('Enterprise_PageCache_Model_Cache')) {
                Enterprise_PageCache_Model_Cache::getCacheInstance()->cleanType('full_page');
            }
        }

        $this->getResponse()->setBody(Zend_Json::encode(['success' => true]));
    }
}