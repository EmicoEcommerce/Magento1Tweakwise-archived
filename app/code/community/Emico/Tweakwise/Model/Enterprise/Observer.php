<?php
/**
 * @author : Edwin Jacobs, email: ejacobs@emico.nl.
 * @copyright : Copyright Emico B.V. 2018.
 */

class Emico_Tweakwise_Model_Enterprise_Observer
{

    /**
     * @return bool
     */
    protected function shouldDisableCache()
    {
        return
            Mage::getEdition() === Mage::EDITION_ENTERPRISE &&
            Mage::app()->getRequest()->isAjax() &&
            Mage::app()->getCacheInstance()->canUse('full_page') &&
            Mage::helper('emico_tweakwise')->isNavigationAjax();
    }

    /**
     * Disable Enterprise PageCache
     *
     * This fix should ban the use of full_page cache only for specific ajax requests.
     *
     * The issue is that when using ajax filtering Magento Enterprise PageCache adds the following header to the wrong cache id:
     * Content-Type: application/json. The cache id this header has been added to is the metadata cache entry for the category page
     * However it should be added to the metadata entry for the ajax request. The ajax requests has its own cache id however it seems that
     * Enterprise will not update it correctly or serve it correctly when asked. The reason for this has to do with the implementation of
     * function Enterprise_PageCache_Model_Processor::_createRequestIds() here url params are stripped of the url and so
     * www.example.com/category/1 and www.example.com/category/1?ajax=1 give the same request id. In the end this request id is not used when saving
     * the response content but is used to save response metadata. The id generated for saving the content is generated (for categories) by:
     * Enterprise_PageCache_Model_Processor_Category::getPageIdInApp() in combination with Enterprise_PageCache_Model_Processor::prepareCacheId.
     * This yields an unique id in which query params are accounted for, however when updating metadata for that request the wrong id is generated
     * as it looks to the id returned by Enterprise_PageCache_Model_Processor::_createRequestIds()
     *
     *
     * @see Enterprise_PageCache_Model_Processor::prepareCacheId()
     * @see Enterprise_PageCache_Model_Processor_Category::getPageIdInApp()
     * @see Enterprise_PageCache_Model_Processor::_createRequestIds()
     * @see Enterprise_PageCache_Model_Processor::setMetadata()
     * @see Enterprise_PageCache_Model_Processor::getMetadata()
     * @see Enterprise_PageCache_Model_Processor::processRequestResponse()
     * @param Varien_Event_Observer $observer
     */
    public function disableFullPageCache(Varien_Event_Observer $observer)
    {
        if ($this->shouldDisableCache()) {
            Mage::app()->getCacheInstance()->banUse('full_page');
        }
    }


}