<?php

/**
 * @author Freek Gruntjes <fgruntjes@emico.nl>
 * @copyright (c) Emico B.V. 2015
 */
class Emico_Tweakwise_Model_Data_Collection extends Varien_Data_Collection
{
    /**
     * @param int $count
     * @return $this
     */
    public function setTotalRecords($count)
    {
        $this->_totalRecords = abs((int)$count);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStoreId()
    {
        return Mage::app()->getStore()->getStoreId();
    }

}