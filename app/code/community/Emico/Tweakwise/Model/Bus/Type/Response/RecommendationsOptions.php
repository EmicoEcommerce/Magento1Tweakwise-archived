<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Model_Bus_Response_NavigationResponse
 *
 * @method Emico_Tweakwise_Model_Bus_Type_RecommendationOption[] getRecommendations();
 */
class Emico_Tweakwise_Model_Bus_Type_Response_RecommendationsOptions extends Emico_Tweakwise_Model_Bus_Type_Abstract
{
    /**
     * {@inheritDoc}
     */
    public function setDataFromXMLElement(SimpleXMLElement $xmlElement)
    {
        $this->setDataFromField($xmlElement, 'recommendations', 'recommendationOption', self::ELEMENT_COUNT_NONE_OR_MORE);

        return $this;
    }
}