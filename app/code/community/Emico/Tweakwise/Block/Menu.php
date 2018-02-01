<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Replacement for catalog.topnav
 */
class Emico_Tweakwise_Block_Menu extends Mage_Core_Block_Template
{
    /**
     * @var Emico_Tweakwise_Model_Bus_Request_Bulknav
     */
    protected $_tweakwiseRequest;

    /**
     * @var Emico_Tweakwise_Model_Bus_Type_Response_Bulknav
     */
    protected $_tweakwiseResponse;

    /**
     * Fetch navigation items
     *
     * @return Emico_Tweakwise_Block_Menu_MainSection[]
     */
    public function getItems()
    {
        if (!$this->hasData('items')) {
            $items = [];
            foreach ($this->getTweakwiseResponse()->getMainSections() as $item) {
                $items[] = $this->createItemBlock($item, count($items));
            }
            $this->setData('items', $items);
        }

        return $this->getData('items');
    }

    /**
     * Uses TweakWise request to fetch response. Only executes the request once
     *
     * @return Emico_Tweakwise_Model_Bus_Type_Response_Bulknav
     */
    protected function getTweakwiseResponse()
    {
        if ($this->_tweakwiseResponse === null) {
            $this->_tweakwiseResponse = $this->getTweakwiseRequest()->execute();
        }

        return $this->_tweakwiseResponse;
    }

    /**
     * Fetch and create if not set TweakWise Bulknav request
     *
     * @return Emico_Tweakwise_Model_Bus_Request_Bulknav
     */
    protected function getTweakwiseRequest()
    {
        if ($this->_tweakwiseRequest === null) {
            $this->_tweakwiseRequest = Mage::getModel('emico_tweakwise/bus_request_bulknav');
            $this->_tweakwiseRequest->setCategory($this->getCategory());
        }

        return $this->_tweakwiseRequest;
    }

    /**
     * Get currently set category
     *
     * @return Mage_Catalog_Model_Category|int
     */
    protected function getCategory()
    {
        if (!$this->hasData('category')) {
            $category = Mage::registry('current_category');
            if ($category) {
                $this->setData('category', $category);
            } else {
                $this->setData('category', Mage::app()->getStore()->getRootCategoryId());
            }
        }

        return $this->getData('category');
    }

    /**
     * Create item block for main section
     *
     * @param Emico_Tweakwise_Model_Bus_Type_MainSection $section
     * @param int $counter
     * @return Emico_Tweakwise_Block_Menu_MainSection
     */
    protected function createItemBlock(Emico_Tweakwise_Model_Bus_Type_MainSection $section, $counter)
    {
        /** @var Emico_Tweakwise_Block_Menu_MainSection $block */
        $block = $this->getLayout()->createBlock('emico_tweakwise/menu_mainSection');
        $block->setMainSection($section);
        $block->setCounter($counter);

        return $block;
    }
}