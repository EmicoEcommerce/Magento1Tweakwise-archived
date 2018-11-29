<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Block_Catalog_Product_List_Toolbar_Pager
 */
class Emico_Tweakwise_Block_Catalog_Product_List_Toolbar_Pager extends Mage_Page_Block_Html_Pager
{
    public function getPreviousPageUrl()
    {
        return $this->getPageUrl($this->getCurrentPage(-1));
    }

    /**
     * Get current collection page
     *
     * @param  int $displacement
     * @return int
     */
    public function getCurrentPage($displacement = 0)
    {
        $currentPage = $this->getResponseProperties()->getCurrentPage();
        if ($currentPage + $displacement < 1) {
            return 1;
        } elseif ($currentPage + $displacement > $this->getLastPageNumber()) {
            return $this->getLastPageNumber();
        } else {
            return $currentPage + $displacement;
        }
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Properties
     */
    protected function getResponseProperties()
    {
        return $this->getResponse()->getProperties();
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Response_Navigation
     */
    protected function getResponse()
    {
        return $this->getLayer()->getTweakwiseResponse();
    }

    /**
     * @return Emico_Tweakwise_Model_Catalog_Layer
     */
    protected function getLayer()
    {
        return Mage::getSingleton('emico_tweakwise/catalog_layer');
    }

    /**
     * Retrieve collection last page number
     *
     * @return int
     */
    public function getLastPageNumber()
    {
        $numberOfPages = $this->getResponseProperties()->getNumberOfPages();

        if ($numberOfPages) {
            return $numberOfPages;
        } else {
            return 1;
        }
    }

    public function getNextPageUrl()
    {
        return $this->getPageUrl($this->getCurrentPage(+1));
    }

    /**
     * @return int
     */
    public function getLastPageNum()
    {
        return $this->getResponseProperties()->getNumberOfPages();
    }

    /**
     * @return int
     */
    public function getFirstNum()
    {
        $properties = $this->getResponseProperties();

        return $properties->getPageSize() * ($properties->getCurrentPage() - 1) + 1;
    }

    /**
     * @return int
     */
    public function getLastNum()
    {
        $properties = $this->getResponseProperties();

        return $properties->getPageSize() * ($properties->getCurrentPage() - 1) + $this->getCollection()->getSize();
    }

    /**
     * @return Mage_Catalog_Model_Product[]|Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getCollection()
    {
        return $this->getLayer()->getProducts();
    }

    /**
     * @return int
     */
    public function getTotalNum()
    {
        return $this->getResponseProperties()->getNumberOfItems();
    }

    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setData('show_amounts', true);
        $this->setData('use_container', true);
        $this->setTemplate('page/html/pager.phtml');
    }

    /**
     * Initialize frame data, such as frame start, frame start etc.
     *
     * @return Mage_Page_Block_Html_Pager
     */
    protected function _initFrame()
    {
        if (!$this->isFrameInitialized()) {
            $start = 0;
            $end = 0;

            $properties = $this->getResponseProperties();
            if ($properties->getNumberOfPages() <= $this->getFrameLength()) {
                $start = 1;
                $end = $properties->getNumberOfPages();
            } else {
                $half = ceil($this->getFrameLength() / 2);
                if ($properties->getCurrentPage() >= $half && $properties->getCurrentPage() <= $properties->getNumberOfPages() - $half) {
                    $start = ($properties->getCurrentPage() - $half) + 1;
                    $end = ($start + $this->getFrameLength()) - 1;
                } elseif ($properties->getCurrentPage() < $half) {
                    $start = 1;
                    $end = $this->getFrameLength();
                } elseif ($properties->getCurrentPage() > ($properties->getNumberOfPages() - $half)) {
                    $end = $properties->getNumberOfPages();
                    $start = $end - $this->getFrameLength() + 1;
                }
            }
            $this->_frameStart = $start;
            $this->_frameEnd = $end;

            $this->_setFrameInitialized(true);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPagerUrl($params = [])
    {
        $query = Mage::helper('emico_tweakwise')->getFilteredQuery();
        $urlParams = [];
        $urlParams['_escape'] = true;
        $urlParams['_use_rewrite'] = true;
        $urlParams['_query']    = array_merge($query, $params);
        return $this->getUrl('*/*/*', $urlParams);
    }
}
