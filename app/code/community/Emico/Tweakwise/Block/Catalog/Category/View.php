<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Block_Catalog_Category_View
 */
class Emico_Tweakwise_Block_Catalog_Category_View extends Mage_Catalog_Block_Category_View
{
    /**
     * {@inheritDoc}
     */
    public function _toHtml()
    {
        return Mage::helper('emico_tweakwise')->isEnabled('navigation') ? parent::_toHtml() : '';
    }
}