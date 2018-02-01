<?php

class Emico_Tweakwise_RecommendationController extends Mage_Core_Controller_Front_Action
{
    /**
     * Get Json response for products
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        $hash = $request->getParam('hash');
        $params = $request->getParams();

        if (!$hash) {
            $this->norouteAction();
            return;
        }

        $helper = Mage::helper('emico_tweakwise');
        if ($helper->getRecommendationAjaxParamHash($params) != $hash) {
            $this->norouteAction();
            return;
        }

        $handles = array_filter(explode(',', $request->getParam('handles')));
        $template = $request->getParam('template');
        if (!$handles && !$template) {
            $this->norouteAction();
            return;
        }

        if ($sku = $request->getParam('product')) {
            /** @var Mage_Catalog_Model_Product $product */
            $product = Mage::getModel('catalog/product');
            $productId = $product->getIdBySku($sku);

            if (!$productId) {
                $this->norouteAction();
                return;
            }

            if (!Mage::helper('catalog/product')->initProduct($productId, $this)) {
                $this->norouteAction();
                return;
            }
        }

        if ($handles) {
            $this->loadLayout($handles);
            $block = $this->getLayout()->getBlock($request->getParam('block'));
        } else {
            $this->loadLayout();
            $block = $this->getLayout()->getBlock('recommendations');
        }

        if (!$block) {
            $this->norouteAction();
            return;
        }

        if (($template = $request->getParam('template')) && $block instanceof Mage_Core_Block_Template) {
            $block->setTemplate($template);
        }
        $block->setData('rule_id', (int)$request->getParam('rule'));
        $result = $block->toHtml();
        $this->getResponse()->setBody($result);
    }
}