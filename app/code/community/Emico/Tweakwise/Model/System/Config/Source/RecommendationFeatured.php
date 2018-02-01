<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Model_System_Config_Source_Template
 */
class Emico_Tweakwise_Model_System_Config_Source_RecommendationFeatured extends Emico_Tweakwise_Model_System_Config_Source_Options
{
    /**
     * {@inheritdoc}
     */
    protected function getRequestModel()
    {
        return 'recommendations_featuredOptions';
    }

    /**
     * @param $response Emico_Tweakwise_Model_Bus_Type_Response_RecommendationsOptions
     * {@inheritdoc}
     */
    protected function parseResult(Emico_Tweakwise_Model_Bus_Type_Abstract $response)
    {
        $recommendations = $response->getRecommendations();
        if (!$recommendations) {
            return [];
        }

        $result = [];
        foreach ($response->getRecommendations() as $recommendation) {
            $result[$recommendation->getId()] = $recommendation->getName();
        }
        return $result;
    }
}