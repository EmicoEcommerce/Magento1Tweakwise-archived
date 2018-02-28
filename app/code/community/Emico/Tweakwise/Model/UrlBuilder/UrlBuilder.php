<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */
class Emico_Tweakwise_Model_UrlBuilder_UrlBuilder
{
    /**
     * @var Emico_Tweakwise_Model_UrlBuilder_Strategy_StrategyInterface[]
     */
    protected $strategies = [];

    /**
     * Emico_Tweakwise_Model_UrlBuilder_UrlBuilder constructor.
     */
    public function __construct()
    {
        $strategies = Mage::getConfig()->loadModulesConfiguration('config.xml')
            ->getNode('emico_tweakwise/urlbuilder_strategies')->asXML();

        $this->addStrategy(Mage::getModel('emico_tweakwise/urlBuilder_strategy_categoryStrategy'));
        //$this->addStrategy(Mage::getModel('emico_tweakwise/urlBuilder_strategy_defaultStrategy'));
    }

    /**
     * @param Emico_Tweakwise_Model_UrlBuilder_Strategy_StrategyInterface $strategy
     */
    public function addStrategy(Emico_Tweakwise_Model_UrlBuilder_Strategy_StrategyInterface $strategy)
    {
        $this->strategies[] = $strategy;
    }

    /**
     * @param Emico_Tweakwise_Model_Catalog_Layer $state
     * @param Emico_Tweakwise_Model_Bus_Type_Attribute $attribute
     * @return null|string
     * @throws Exception
     */
    public function buildUrl(Emico_Tweakwise_Model_Bus_Type_Facet $facet, Emico_Tweakwise_Model_Bus_Type_Attribute $attribute)
    {
        $state = Mage::getModel('emico_tweakwise/catalog_layer');
        foreach ($this->strategies as $strategy) {
            $url = $strategy->buildUrl($state, $facet, $attribute);
            if ($url !== null) {
                return $url;
            }
        }
        throw new \Exception('No strategy was able to generate a URL');
    }
}