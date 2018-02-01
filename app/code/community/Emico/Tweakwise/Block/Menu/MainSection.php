<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Main section menu block
 *
 * @method Emico_Tweakwise_Model_Bus_Type_MainSection getMainSection();
 * @method Emico_Tweakwise_Block_Menu_MainSection setMainSection(Emico_Tweakwise_Model_Bus_Type_MainSection $section);
 * @method string getCounter();
 * @method Emico_Tweakwise_Block_Menu_MainSection setCounter(string $count);
 */
class Emico_Tweakwise_Block_Menu_MainSection extends Mage_Core_Block_Template
{
    /**
     * {@inheritDoc}
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('emico_tweakwise/menu/mainsection.phtml');
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getMainSection()->getTitle();
    }

    /**
     * @return string
     */
    public function getSectionUrl()
    {
        return $this->getMainSection()->getUrl();
    }

    /**
     * @return Emico_Tweakwise_Block_Menu_Subsection[][]
     */
    public function getItems()
    {
        if (!$this->hasData('items')) {
            $items = [];
            foreach ($this->getMainSection()->getSubsections() as $item) {
                if (!isset($items[$item->getColumn()])) {
                    $items[$item->getColumn()] = [];
                }
                $items[$item->getColumn()][] = $this->createItemBlock($item, count($items));
            }
            $this->setData('items', $items);
        }

        return $this->getData('items');
    }

    /**
     * @param Emico_Tweakwise_Model_Bus_Type_Subsection $section
     * @param int $counter
     * @return Emico_Tweakwise_Block_Menu_MainSection
     */
    protected function createItemBlock(Emico_Tweakwise_Model_Bus_Type_Subsection $section, $counter)
    {
        /** @var Emico_Tweakwise_Block_Menu_Subsection $block */
        $block = $this->getLayout()->createBlock('emico_tweakwise/menu_subsection');
        $block->setSubsection($section);
        $block->setCounter($this->getCounter() . '-' . $counter);

        return $block;
    }

    /**
     * @return Emico_Tweakwise_Model_Bus_Type_Banner[]
     */
    public function getBanners()
    {
        if (!$this->hasData('banners')) {
            $items = [];
            foreach ($this->getMainSection()->getBanners() as $item) {
                if ($_bannerLocation = Mage::helper('emico_tweakwise')->getBannerLocation()) {
                    $item['image_url'] = $_bannerLocation . $item['title'];
                } else {
                    $item['image_url'] = Mage::getBaseUrl() . $item['title'];
                }
                $items[] = $item;
            }

            $this->setData('banners', $items);
        }

        return $this->getData('banners');
    }
}