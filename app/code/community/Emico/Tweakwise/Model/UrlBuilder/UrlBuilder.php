<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */
class Emico_Tweakwise_Model_UrlBuilder_UrlBuilder
{
    /**
     * @param Emico_Tweakwise_Model_Catalog_Layer $state
     * @param Emico_Tweakwise_Model_Bus_Type_Attribute $attribute
     * @return null|string
     * @throws Exception
     */
    public function buildUrl(Emico_Tweakwise_Model_Bus_Type_Facet $facet = null, Emico_Tweakwise_Model_Bus_Type_Attribute $attribute = null)
    {
        $helper = Mage::helper('emico_tweakwise/uriStrategy');

        $state = Mage::getSingleton('emico_tweakwise/catalog_layer');
        foreach ($helper->getActiveStrategies() as $strategy) {
            $url = $strategy->buildUrl($state, $facet, $attribute);
            if ($url !== null) {
                $url = new Varien_Object(['url' => $url]);
                Mage::dispatchEvent('tweakwise_urlbuilder_buildurl', ['strategy' => $strategy, 'url' => $url]);
                return $url->getData('url');
            }
        }
        throw new \Exception('No strategy was able to generate a URL');
    }
}