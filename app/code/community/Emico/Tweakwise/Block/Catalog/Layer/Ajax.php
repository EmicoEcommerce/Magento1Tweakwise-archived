<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Block_Catalog_Layer_Ajax
 *
 * @method string getLinkSelector();
 * @method $this setLinkSelector(string $selector);
 */
class Emico_Tweakwise_Block_Catalog_Layer_Ajax extends Mage_Core_Block_Template
{
    /**
     * @var array
     */
    protected $blockSelectors = [];

    /**
     * @param string $nameInLayout
     * @param string $cssSelector
     * @return $this
     */
    public function addBlockSelector($nameInLayout, $cssSelector)
    {
        $this->blockSelectors[$nameInLayout] = $cssSelector;
        return $this;
    }

    /**
     * @return string
     */
    public function getOptionsJson()
    {
        /** @var Mage_Core_Helper_Data $helper */
        $helper = Mage::helper('core');
        return $helper->jsonEncode($this->getOptionsArray());
    }

    /**
     * @return array
     */
    protected function getOptionsArray()
    {
        return [
            'blocks' => $this->getBlockSelectors(),
            'linkSelector' => $this->getLinkSelector(),
        ];
    }

    /**
     * @return array
     */
    public function getBlockSelectors()
    {
        return $this->blockSelectors;
    }
}
