<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Model_Bus_Request_Navigation
 */
class Emico_Tweakwise_Model_Bus_Request_Recommendations_Featured extends Emico_Tweakwise_Model_Bus_Request_Abstract
{
    /**
     * @var int
     */
    protected $_ruleId;

    /**
     * {@inheritDoc}
     *
     * @return
     */
    public function execute($store = null)
    {
        $ruleId = $this->getRuleId();
        $this->setClientUrl('{baseUrl}/{service}/{key}/{ruleId}', ['ruleId' => $ruleId]);

        return parent::execute($store);
    }

    /**
     * @return int
     */
    public function getRuleId()
    {
        return $this->_ruleId;
    }

    /**
     * @param int $ruleId
     * @return $this
     */
    public function setRuleId($ruleId)
    {
        $this->_ruleId = $ruleId ? (int)$ruleId : null;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function getResponseModel()
    {
        return 'response_recommendations';
    }

    /**
     * {@inheritDoc}
     */
    protected function getServiceKey()
    {
        return 'recommendations/featured';
    }
}
