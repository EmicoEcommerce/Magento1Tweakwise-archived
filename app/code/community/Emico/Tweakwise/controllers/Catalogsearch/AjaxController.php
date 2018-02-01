<?php

require('Mage/CatalogSearch/controllers/AjaxController.php');

class Emico_Tweakwise_Catalogsearch_AjaxController extends Mage_CatalogSearch_AjaxController
{
    /**
     * Load emico catalog search auto complete block
     */
    public function suggestAction()
    {
        if (!$this->getRequest()->getParam('q', false)) {
            $this->getResponse()->setRedirect(Mage::getSingleton('core/url')->getBaseUrl());
        }

        $layout = $this->getLayout();
        try {
            if (Mage::helper('emico_tweakwise')->isEnabled('autocomplete')) {
                $html = $layout->createBlock('emico_tweakwise/catalogsearch_autocomplete')->toHtml();
            } else {
                $html = $layout->createBlock('catalogsearch/autocomplete')->toHtml();
            }
        } catch (Emico_Tweakwise_Model_Exception $e) {
            Mage::logException($e);
            $html = $layout->createBlock('catalogsearch/autocomplete')->toHtml();
        }

        $this->getResponse()->setBody($html);
    }
}
