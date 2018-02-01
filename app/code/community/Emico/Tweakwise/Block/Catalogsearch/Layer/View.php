<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Block_Catalogsearch_Layer_View
 */
class Emico_Tweakwise_Block_Catalogsearch_Layer_View extends Emico_Tweakwise_Block_Catalog_Layer_Abstract
{
    /**
     * @return string
     */
    protected function _toHtml()
    {
        if (!Mage::helper('emico_tweakwise')->isEnabled('search')) {
            return '';
        }

        return parent::_toHtml();
    }
} 