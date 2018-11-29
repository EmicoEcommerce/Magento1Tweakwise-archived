<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Block_Catalog_Layer_Facet_Category
 */
class Emico_Tweakwise_Block_Catalog_Layer_Facet_Category extends Emico_Tweakwise_Block_Catalog_Layer_Facet_Attribute
{
    /**
     * @var
     */
    protected $_activeCategories;

    /**
     * {@inheritDoc}
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('emico_tweakwise/catalog/layer/facet/attribute.phtml');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        if ($this->_activeCategories === null) {
            $attributes = parent::getAttributes();
            $this->_activeCategories = [];

            foreach ($attributes as $attribute) {
                $this->_activeCategories[] = $attribute;
            }
        }

        return $this->_activeCategories;
    }
}
