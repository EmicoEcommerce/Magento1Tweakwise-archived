<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Model_Bus_Request_Template
 */
class Emico_Tweakwise_Model_Bus_Request_Template extends Emico_Tweakwise_Model_Bus_Request_Abstract
{
    /**
     * {@inheritDoc}
     */
    protected function getServiceKey()
    {
        return 'catalog/templates';
    }

    /**
     * {@inheritDoc}
     */
    protected function getResponseModel()
    {
        return 'response_template';
    }
}