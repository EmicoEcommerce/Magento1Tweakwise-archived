<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */
class Emico_Tweakwise_Helper_UriStrategy
{
    /**
     * @var Emico_Tweakwise_Model_UrlBuilder_Strategy_StrategyInterface[]
     */
    protected $_activeStrategies = [];

    /**
     * @return Emico_Tweakwise_Model_UrlBuilder_Strategy_StrategyInterface[]
     * @throws Emico_Tweakwise_Model_Exception
     */
    public function getActiveStrategies()
    {
        if (!$this->_activeStrategies) {
            $strategies = Mage::getConfig()->getNode('global/emico_tweakwise/urlbuilder_strategies')->children();

            $selectedStrategy = Mage::getStoreConfig('emico_tweakwise/navigation/uri_strategy');
            if (empty($selectedStrategy)) {
                $selectedStrategy = 'queryParam';
            }

            foreach ($strategies as $name => $strategyNode) {

                $active = (bool) (string) $strategyNode->active;
                $selectable = (bool) (string) $strategyNode->selectable;

                // The strategy is not activated but can be choosen by the user
                if (!$active && $selectable && $selectedStrategy == $name) {
                    $active = true;
                }

                if (!$active) {
                    continue;
                }

                $className = (string) $strategyNode->class;
                $strategy = Mage::getModel($className);

                $this->registerStrategy($strategy, $name);
            }
        }
        return $this->_activeStrategies;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasActiveStrategy($name)
    {
        return isset($this->_activeStrategies[$name]);
    }

    /**
     * @param Emico_Tweakwise_Model_UrlBuilder_Strategy_StrategyInterface $strategy
     * @param string $name
     * @throws Emico_Tweakwise_Model_Exception
     */
    private function registerStrategy(Emico_Tweakwise_Model_UrlBuilder_Strategy_StrategyInterface $strategy, $name)
    {
        // Path strategy is only implemented for Category pages. Fallback to Query param strategy if not on category page
        if (Mage::registry('current_category') === null && $strategy instanceof Emico_Tweakwise_Model_UrlBuilder_Strategy_PathStrategy) {
            $strategy = Mage::getModel('emico_tweakwise/urlBuilder_strategy_queryParamStrategy');
        }

        $this->_activeStrategies[$name] = $strategy;
    }
}