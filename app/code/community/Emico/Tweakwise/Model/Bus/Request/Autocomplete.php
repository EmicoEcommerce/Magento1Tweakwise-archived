<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Class Emico_Tweakwise_Model_Bus_Request_Autocomplete
 */
class Emico_Tweakwise_Model_Bus_Request_Autocomplete extends Emico_Tweakwise_Model_Bus_Request_Abstract
{
    /**
     * @param Mage_Catalog_Model_Category|int $category
     * @return Emico_Tweakwise_Model_Bus_Request_Autocomplete
     */
    public function setCategory($category)
    {
        if ($category instanceof Mage_Catalog_Model_Category) {
            $category = $category->getId();
        }

        $this->setParameter('tn_cid', (int)$category);

        return $this;
    }

    /**
     * @param bool $getProducts
     * @return Emico_Tweakwise_Model_Bus_Request_Autocomplete
     */
    public function setGetProducts($getProducts)
    {
        $this->setParameter('tn_items', $getProducts ? 'true' : 'false');

        return $this;
    }

    /**
     * @param bool $getSuggestions
     * @return Emico_Tweakwise_Model_Bus_Request_Autocomplete
     */
    public function setGetSuggestions($getSuggestions)
    {
        $this->setParameter('tn_suggestions', $getSuggestions ? 'true' : 'false');

        return $this;
    }

    /**
     * @param bool $isInstant
     * @return Emico_Tweakwise_Model_Bus_Request_Autocomplete
     */
    public function setIsInstant($isInstant)
    {
        $this->setParameter('tn_instant', $isInstant ? 'true' : 'false');

        return $this;
    }

    /**
     * @param string $query
     * @return Emico_Tweakwise_Model_Bus_Request_Autocomplete
     */
    public function setQuery($query)
    {
        $this->setParameter('tn_q', (string)$query);

        return $this;
    }

    /**
     * @param int $maxResult
     */
    public function setMaxResult($maxResult)
    {
        $maxResult = (int)$maxResult;
        if ($maxResult == 0) {
            $maxResult = null;
        }
        $this->setParameter('tn_maxresults', $maxResult);
    }

    /**
     * {@inheritDoc}
     */
    protected function getServiceKey()
    {
        return 'autocomplete';
    }
}
