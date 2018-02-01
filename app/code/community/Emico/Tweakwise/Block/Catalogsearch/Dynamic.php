<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Block_CatalogSearch_Dynamic
 */
class Emico_Tweakwise_Block_CatalogSearch_Dynamic extends Mage_Core_Block_Template
{
    /**
     * @return mixed
     */
    public function getDynamicSearchUrl()
    {

        return 'http://navigator-dynamic.tweakwise.com/magento/' . Mage::getStoreConfig('emico_tweakwise/global/key') . '/';
    }

    /**
     * @return Mage_CatalogSearch_Helper_Data
     */
    public function getCatalogSearchHelper()
    {
        return $this->helper('catalogsearch');
    }

    /**
     * @return string
     */
    public function _toHtml()
    {

        return parent::_toHtml();
    }

    /**
     * @param string $config
     * @return bool
     */
    protected function getConfig($config)
    {
        return Mage::getStoreConfig('emico_tweakwise/dynamicsearch/' . $config);
    }
}
