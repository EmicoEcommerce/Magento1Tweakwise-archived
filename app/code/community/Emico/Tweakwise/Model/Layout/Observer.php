<?php
/**
 * @author : Edwin Jacobs, email: ejacobs@emico.nl.
 * @copyright : Copyright Emico B.V. 2018.
 */

class Emico_Tweakwise_Model_Layout_Observer
{
    /**
     * Populate helper with categoryData
     * @param Varien_Event_Observer $observer
     */
    public function loadCategoryData(Varien_Event_Observer $observer)
    {
        /** @var Emico_Tweakwise_Model_Catalog_Layer $layer */
        $layer = Mage::getSingleton('emico_tweakwise/catalog_layer');
        if (!$layer->hasTweakwiseResponse()) {
            return;
        }

        $tweakwiseResponse = $layer->getTweakwiseResponse();
        $categoryFacet = $this->getCategoryFacet($tweakwiseResponse);
        if (!$categoryFacet) {
            return;
        }
        $categories = $this->getMagentoCategories($categoryFacet);

        $tweakwiseHelper = Mage::helper('emico_tweakwise');
        // Initialise all used categories for faster access in templates
        $tweakwiseHelper->addToCategoryCollection($categories);
    }

    /**
     * @param Emico_Tweakwise_Model_Bus_Type_Response_Navigation $tweakwiseResponse
     * @return Emico_Tweakwise_Model_Bus_Type_Facet
     */
    protected function getCategoryFacet(Emico_Tweakwise_Model_Bus_Type_Response_Navigation $tweakwiseResponse)
    {
        $categoryFacets = array_filter(
            $tweakwiseResponse->getFacets(),
            function(Emico_Tweakwise_Model_Bus_Type_Facet $facet)
            {
                return $facet->getFacetSettings()->getSource() === Emico_Tweakwise_Model_Bus_Type_Facet_Settings::FACET_SOURCE_CATEGORY;
            }
        );
        if (empty($categoryFacets)) {
            return null;
        }

        return reset($categoryFacets);
    }

    /**
     * Retrieve magento attribute Ids
     *
     * @param Emico_Tweakwise_Model_Bus_Type_Facet $categoryItem
     * @return array
     */
    protected function getMagentoCategories(Emico_Tweakwise_Model_Bus_Type_Facet $categoryItem)
    {
        $tweakwiseExportHelper = Mage::helper('emico_tweakwiseexport');
        $tweakwiseCategoryIds = [];
        foreach ($categoryItem->getAttributes() as $tweakwiseCategory) {
            $this->getTweakwiseCategoryIds($tweakwiseCategory, $tweakwiseCategoryIds);
        }
        $categoryIds = array_map(
            function ($tweakwiseCategoryId) use ($tweakwiseExportHelper)
            {
                return $tweakwiseExportHelper->fromStoreId($tweakwiseCategoryId);
            },
            $tweakwiseCategoryIds
        );

        return Mage::getModel('catalog/category')->getCollection()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('entity_id', ['in' => $categoryIds])
            ->joinUrlRewrite()
            ->getItems();
    }

    /**
     * @param Emico_Tweakwise_Model_Bus_Type_Attribute $tweakwiseCategory
     */
    protected function getTweakwiseCategoryIds(Emico_Tweakwise_Model_Bus_Type_Attribute $tweakwiseCategory, array &$return)
    {
        $return[] = $tweakwiseCategory->getAttributeId();

        if ($tweakwiseCategory->getChildren()) {
            foreach ($tweakwiseCategory->getChildren() as $tweakwiseChild) {
                $this->getTweakwiseCategoryIds($tweakwiseChild, $return);
            }
        }
    }
}