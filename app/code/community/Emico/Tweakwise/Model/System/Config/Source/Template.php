<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Model_System_Config_Source_Template
 */
class Emico_Tweakwise_Model_System_Config_Source_Template extends Emico_Tweakwise_Model_System_Config_Source_Options
{
    /**
     * {@inheritdoc}
     */
    protected function getRequestModel()
    {
        return 'template';
    }

    /**
     * @param $response Emico_Tweakwise_Model_Bus_Type_Response_Template
     * {@inheritdoc}
     */
    protected function parseResult(Emico_Tweakwise_Model_Bus_Type_Abstract $response)
    {
        $templates = $response->getTemplates();
        if (!$templates) {
            return [];
        }

        $result = [];
        foreach ($response->getTemplates() as $template) {
            $result[$template->getTemplateId()] = $template->getName();
        }
        return $result;
    }
}