<?php

require('Mage/CatalogSearch/controllers/ResultController.php');

class Emico_Tweakwise_Catalogsearch_ResultController extends Mage_CatalogSearch_ResultController
{
    /**
     * {@inheritDoc}
     */
    public function indexAction()
    {
        if (!Mage::helper('emico_tweakwise')->isEnabled('search')) {
            parent::indexAction();

            return;
        }

        try {
            // Check redirects
            $response = Mage::getSingleton('emico_tweakwise/catalog_layer')
                ->setTemplateId(Mage::helper('emico_tweakwise')->getSearchTemplateId())
                ->getTweakwiseResponse();
            if ($response instanceof Emico_Tweakwise_Model_Bus_Type_Response_Navigation) {
                $redirects = $response->getRedirects();
                if ($redirects && count($redirects)) {
                    $redirect = $redirects[0];
                    $this->getResponse()->setRedirect($redirect->getUrl());

                    return;
                }
            }
        } catch (Exception $e) {
            Mage::logException($e);
            parent::indexAction();

            return;
        }


        $query = Mage::helper('catalogsearch')->getQuery();
        /* @var $query Mage_CatalogSearch_Model_Query */

        $query->setStoreId(Mage::app()->getStore()->getId());

        if ($query->getQueryText() != '') {
            Mage::helper('catalogsearch')->isMinQueryLength();
            Mage::helper('catalogsearch')->checkNotes();

            $this->loadLayout();
            $this->_initLayoutMessages('catalog/session');
            $this->_initLayoutMessages('checkout/session');
            $this->renderLayout();
        } else {
            $this->_redirectReferer();
        }
    }

    /**
     * This is done so that te result action actually differs from the normal result action so the Magento standard layer does not load.
     *
     * {@inheritDoc}
     */
    public function addActionLayoutHandles()
    {
        parent::addActionLayoutHandles();
        if (!Mage::helper('emico_tweakwise')->isEnabled('search')) {
            return $this;
        }

        $update = $this->getLayout()->getUpdate();
        if (!in_array('catalogsearch_result_index', $update->getHandles())) {
            return $this;
        }

        $update->removeHandle('catalogsearch_result_index');
        $update->addHandle('tweakwise_catalogsearch_result_index');

        return $this;
    }
}
