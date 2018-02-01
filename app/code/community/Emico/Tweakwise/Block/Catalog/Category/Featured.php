<?php

/**
 * @author Freek Gruntjes <fgruntjes@emico.nl>
 * @copyright (c) Emico B.V. 2016
 */

/**
 * Class Emico_Tweakwise_Block_Catalog_Product_List_Featured
 *
 */
class Emico_Tweakwise_Block_Catalog_Category_Featured extends Emico_Tweakwise_Block_Catalog_Product_List_Featured
{
    /**
     * @return int
     */
    public function getRuleId()
    {
        $category = $this->getCurrentCategory();
        if ($category) {
            $ruleId = (int)$category->getData(Emico_Tweakwise_Helper_Data::FEATURED_TEMPLATE_ATTRIBUTE);
            if ($ruleId) {
                return $ruleId;
            }
        }

        return (int)Mage::getStoreConfig('emico_tweakwise/recommendations/category_template');
    }

    /**
     * Retrieve current category model object
     *
     * @return Mage_Catalog_Model_Category
     */
    public function getCurrentCategory()
    {
        if (!$this->hasData('current_category')) {
            $this->setData('current_category', Mage::registry('current_category'));
        }
        return $this->getData('current_category');
    }
}