<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */
interface Emico_Tweakwise_Model_UrlBuilder_Strategy_RoutingStrategyInterface
{
    /**
     * If you need to do custom
     *
     * @return bool
     */
    public function matchUrl(Zend_Controller_Request_Http $request);
}