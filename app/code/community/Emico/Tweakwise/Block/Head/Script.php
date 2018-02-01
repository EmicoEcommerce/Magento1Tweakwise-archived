<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Add script tag
 */
class Emico_Tweakwise_Block_Head_Script extends Mage_Core_Block_Template
{
    /**
     * Get key
     */

    public function getKey()
    {
        return Mage::getStoreConfig('emico_tweakwise/global/key');
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return Mage::helper('emico_tweakwise')->isEnabled('dynamicsearch');
    }
}