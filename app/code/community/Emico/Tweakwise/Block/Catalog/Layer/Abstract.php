<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Block_Catalog_Layer_Abstract
 */
class Emico_Tweakwise_Block_Catalog_Layer_Abstract extends Mage_Core_Block_Template
{
    /**
     * @return Emico_Tweakwise_Model_Catalog_Layer
     */
    public function getLayer()
    {
        return Mage::getSingleton('emico_tweakwise/catalog_layer');
    }
} 