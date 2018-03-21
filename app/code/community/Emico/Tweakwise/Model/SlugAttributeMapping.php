<?php
/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 * @method string getAttributeCode()
 * @method string getAttributeValue()
 * @method string getSlug()
 */
class Emico_Tweakwise_Model_SlugAttributeMapping extends Mage_Core_Model_Abstract
{
    /**
     * @var
     */
    protected $mapping;

    /**
     * Init model
     */
    protected function _construct()
    {
        $this->_init('emico_tweakwise/slugAttributeMapping');
    }

    /**
     * @param $code
     * @param $value
     * @return string
     */
    public function getSlugForAttribute($code, $value)
    {
        $mapping = $this->getMapping();
        if (!isset($mapping[$code][$value])) {
            return $value;
        }
        return $mapping[$code][$value];
    }

    /**
     * @param string $code
     * @param string $requestedSlug
     * @return int|null|string
     * @throws Emico_Tweakwise_Model_Exception
     */
    public function getAttributeValueBySlug($code, $requestedSlug)
    {
        $mapping = $this->getMapping();
        if (!isset($mapping[$code])) {
            throw new Emico_Tweakwise_Model_Exception('No slugs defined for attributeCode ' . $code);
        }
        $attributeSlugs = $mapping[$code];
        foreach ($attributeSlugs as $attributeValue => $slug) {
            if ($requestedSlug === $slug) {
                return $attributeValue;
            }
        }
        throw new Emico_Tweakwise_Model_Exception(sprintf('No slug found for attributeCode "%s" and slug "%s"', $code, $requestedSlug));
    }

    /**
     * @return bool
     */
    public function clearCache()
    {
        return $this->getCacheInstance()->remove($this->getCollection()->getCacheKey());
    }

    /**
     * @return Zend_Cache_Core
     */
    protected function getCacheInstance()
    {
        return Mage::app()->getCache();
    }

    /**
     * @return array
     */
    protected function getMapping()
    {
        if ($this->mapping === null) {

            $this->getCollection()->initCache(
                $this->getCacheInstance(),
                null,
                ['collections', 'tweakwise_slugs']
            );

            $collection = $this->getCollection()->load();
            foreach ($collection as $item) {
                $this->mapping[$item->getAttributeCode()][$item->getAttributeValue()] = $item->getSlug();
            }
        }
        return $this->mapping;
    }
}