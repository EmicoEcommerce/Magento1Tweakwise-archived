<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Block_Catalog_Layer_Facet_Slider
 */
class Emico_Tweakwise_Block_Catalog_Layer_Facet_Slider extends Emico_Tweakwise_Block_Catalog_Layer_Facet_Attribute
{
    /**
     * {@inheritDoc}
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('emico_tweakwise/catalog/layer/facet/slider.phtml');
    }

    /**
     * @throws BadMethodCallException
     * @return array
     */
    public function getBounds()
    {
        $value = [];
        foreach ($this->getAttributes() as $attribute) {
            if ($attribute->getIsSelected()) {
                continue;
            }
            $value[] = $attribute->getTitle();
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function getFacetUrl(Emico_Tweakwise_Model_Bus_Type_Attribute $attribute, $urlKey = null)
    {
        $facetSettings = $this->getFacetSettings();
        $urlKey = $facetSettings->getUrlKey();

        return Mage::getUrl('*/*/*', [
            '_current' => true,
            '_use_rewrite' => true,
            '_query' => [
                'p' => null,
                'ajax' => null,
                $urlKey => null,
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getPriceUrl()
    {
        $facetSettings = $this->getFacetSettings();
        $urlKey = $facetSettings->getUrlKey();

        return Mage::getUrl('*/*/*', [
            '_current' => true,
            '_use_rewrite' => true,
            '_query' => [
                'p' => null,
                $urlKey => 'from-to',
            ],
        ]);
    }
}
