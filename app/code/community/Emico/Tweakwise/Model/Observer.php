<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Model_Observer
 */
class Emico_Tweakwise_Model_Observer
{
    /**
     * Set category template to tweakwise request
     *
     * @param Varien_Event_Observer $observer
     */
    public function setCategoryTemplate(Varien_Event_Observer $observer)
    {
        $category = $observer->getData('category');
        if ($templateId = $category->getData('tweakwise_template')) {
            Mage::getSingleton('emico_tweakwise/catalog_layer')->setTemplateId($templateId);
        }
    }

    /**
     * Update response to send json data for requested blocks
     */
    public function handleAjaxRequest()
    {
        if (!Mage::helper('emico_tweakwise')->isNavigationAjax()) {
            return;
        }

        $layout = Mage::app()->getLayout();
        $ajaxBlock = $layout->getBlock('emico_tweakwise.catalog.layer.ajax');
        if (!$ajaxBlock instanceof Emico_Tweakwise_Block_Catalog_Layer_Ajax) {
            return;
        }

        if (!Mage::app()->getRequest()->isXmlHttpRequest()) {
            return;
        }

        $urlModel = Mage::getSingleton('core/url');
        $app = Mage::app();
        $app->setUseSessionVar(false);

        $blocks = array_keys($ajaxBlock->getBlockSelectors());
        $blocksHtml = [];
        foreach ($blocks as $nameInLayout) {
            $block = $layout->getBlock($nameInLayout);
            if (!$block) {
                continue;
            }

            $html = $block->toHtml();
            $html = $urlModel->sessionUrlVar($html);
            $blocksHtml[$nameInLayout] = $html;
        }

        $responseBody = Mage::helper('core')->jsonEncode(['blocks' => $blocksHtml]);

        /** @var Mage_Core_Block_Text $newRoot */
        $newRoot = $layout->createBlock('core/text', 'root');
        $newRoot->setText($responseBody);

        $layout->setBlock('root', $newRoot);
        Mage::app()->getResponse()->setHeader('Content-Type', 'application/json');
        Mage::dispatchEvent('emico_tweakwise_handle_ajax_request_after');
    }

    /**
     * @param Mage_Core_Controller_Varien_Action $controller
     */
    protected function redirect(Mage_Core_Controller_Varien_Action $controller)
    {
        $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
        $response = $controller->getResponse();
        $request = $controller->getRequest();

        $query = ['ajax' => 'true'];
        if (!$request->isXmlHttpRequest()) {
            $query = [
                'json' => null,
                'ajax' => null,
            ];
        }

        $response->setRedirect(Mage::getUrl('*/*/*', array(
            '_current' => true,
            '_use_rewrite' => true,
            '_query' => $query
        )));
    }

    /**
     * Redirect to non json=true / ajax=true version if not ajax request etc.
     *
     * @param Varien_Event_Observer $observer
     */
    public function ensureAjaxOrRedirect(Varien_Event_Observer $observer)
    {
        if (!Mage::helper('emico_tweakwise')->isNavigationAjax()) {
            return;
        }

        /** @var Mage_Core_Controller_Varien_Action $controller */
        $controller = $observer->getData('controller_action');
        $request = $controller->getRequest();

        if ($request->isXmlHttpRequest() && !$request->getParam('json') && !$request->getParam('ajax')) {
            $this->redirect($controller);
        } elseif (!$request->isXmlHttpRequest() && ($request->getParam('json') || $request->getParam('ajax'))) {
            $this->redirect($controller);
        }
    }
}