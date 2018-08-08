<?php
/**
 * Magmodules.eu - http://www.magmodules.eu
 *
 * NOTICE OF LICENSE
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@magmodules.eu so we can send you a copy immediately.
 *
 * @category      Magmodules
 * @package       Magmodules_Channable
 * @author        Magmodules <info@magmodules.eu)
 * @copyright     Copyright (c) 2017 (http://www.magmodules.eu)
 * @license       http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 */

class Emico_Tweakwise_Block_Adminhtml_Config_Form_Field_FollowWhitelist
    extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{

    protected $_renders = array();

    /**
     * Magmodules_Channable_Block_Adminhtml_Config_Form_Field_Extra constructor.
     */
    public function __construct()
    {
        $layout = Mage::app()->getFrontController()->getAction()->getLayout();
        $rendererTypes = $layout->createBlock(
            'channable/adminhtml_config_form_renderer_select',
            '',
            ['is_render_to_js_template' => true]
        );
        $rendererTypes->setOptions(
            Mage::getModel('tegeldepot_channable/adminhtml_system_config_source_imageType')->toOptionArray()
        );

        $this->addColumn(
            'filter1', [
                'label'    => 'Filter 1',
                'style'    => 'width:120px'
            ]
        );

        $this->addColumn(
            'filter2', [
                'label' => 'Filter 2',
                'style' => 'width:120px',
            ]
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('channable')->__('Add filter combination');
        parent::__construct();
    }

    /**
     * @param Varien_Object $row
     */
    protected function _prepareArrayRow(Varien_Object $row)
    {
        foreach ($this->_renders as $key => $render) {
            $row->setData('option_extra_attr_' . $render->calcOptionHash($row->getData($key)), 'selected="selected"');
        }
    }

}