<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Model_Bus_Request_Bulknav
 */
class Emico_Tweakwise_Model_Bus_Request_Bulknav extends Emico_Tweakwise_Model_Bus_Request_Abstract
{
    /**
     * @param Mage_Catalog_Model_Category|int $category
     * @return Emico_Tweakwise_Model_Bus_Request_Bulknav
     */
    public function setCategory($category)
    {
        if ($category instanceof Mage_Catalog_Model_Category) {
            $store = $category->getStore();
            $category = $category->getId();
        } else {
            $store = Mage::app()->getStore();
        }
        $categoryId = Mage::helper('emico_tweakwiseexport')->toStoreId($store, $category);

        $this->setParameter('tn_cid', (int)$categoryId);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function getServiceKey()
    {
        return 'bulknav';
    }
}