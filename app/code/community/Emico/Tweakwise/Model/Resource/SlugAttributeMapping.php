<?php
/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */ 
class Emico_Tweakwise_Model_Resource_SlugAttributeMapping extends Mage_Core_Model_Resource_Db_Abstract
{

    protected function _construct()
    {
        $this->_init('emico_tweakwise/slug_attribute_mapping', 'mapping_id');
    }



}