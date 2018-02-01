<?php

require('Mage/Catalog/controllers/CategoryController.php');

class Emico_Tweakwise_Catalog_CategoryController extends Mage_Catalog_CategoryController
{
    /**
     * {@inheritDoc}
     */
    public function loadLayoutUpdates()
    {
        if (!Mage::helper('emico_tweakwise')->isEnabled('navigation')) {
            return parent::loadLayoutUpdates();
        }

        // Remove magento layout and replace with tweakwise handle
        $layoutUpdate = $this->getLayout()->getUpdate();
        if (!in_array('catalog_category_layered', $layoutUpdate->getHandles())) {
            return parent::loadLayoutUpdates();
        }
        $layoutUpdate->removeHandle('catalog_category_layered');
        $layoutUpdate->addHandle('catalog_category_layered');

        return parent::loadLayoutUpdates();
    }
}
