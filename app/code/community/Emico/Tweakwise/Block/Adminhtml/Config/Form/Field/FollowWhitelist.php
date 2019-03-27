<?php

class Emico_Tweakwise_Block_Adminhtml_Config_Form_Field_FollowWhitelist
    extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{

    /**
     * Emico_Tweakwise_Block_Adminhtml_Config_Form_Field_FollowWhitelist constructor.
     */
    public function __construct()
    {
        $this->addColumn(
            'filter1', [
                'label' => 'Filter 1',
                'style' => 'width:120px'
            ]
        );

        $this->addColumn(
            'filter2', [
                'label' => 'Filter 2',
                'style' => 'width:120px',
            ]
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('emico_tweakwise')->__('Add filter combination');
        parent::__construct();
    }

}
