<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Block_Catalog_Layer_Facet_Color
 */
class Emico_Tweakwise_Block_Catalog_Layer_Facet_Color extends Emico_Tweakwise_Block_Catalog_Layer_Facet_Attribute
{
    /**
     * @var Emico_Tweakwise_Model_Bus_Type_Facet
     */
    protected $_facet;

    /**
     * {@inheritDoc}
     */
    public function _construct()
    {
        parent::_construct();

        $template = Mage::helper('emico_tweakwise')->isImageSwatchActive() ? 'emico_tweakwise/catalog/layer/facet/swatch/image.phtml' : 'emico_tweakwise/catalog/layer/facet/swatch/css.phtml';
        $this->setTemplate($template);
    }
} 