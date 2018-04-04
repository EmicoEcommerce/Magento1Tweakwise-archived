<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Model_System_Config_Source_Template
 */
class Emico_Tweakwise_Model_System_Config_Source_UriStrategy extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getAllOptions();
    }

    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $options = [];
        $strategies = Mage::getConfig()->loadModulesConfiguration('config.xml')
            ->getNode('emico_tweakwise/urlbuilder_strategies')->children();

        foreach ($strategies as $name => $strategyNode) {
            if (!(bool) (string) $strategyNode->selectable) {
                continue;
            }

            $options[$name] = (string) $strategyNode->label;
        }
        return $options;
    }
}