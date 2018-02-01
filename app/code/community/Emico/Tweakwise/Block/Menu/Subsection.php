<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * SubSection menu block
 *
 * @method Emico_Tweakwise_Model_Bus_Type_Subsection getSubsection();
 * @method Emico_Tweakwise_Block_Menu_Subsection setSubsection(Emico_Tweakwise_Model_Bus_Type_Subsection $section);
 * @method string getCounter();
 * @method Emico_Tweakwise_Block_Menu_Subsection setCounter(string $count);
 */
class Emico_Tweakwise_Block_Menu_Subsection extends Mage_Core_Block_Template
{
    /**
     * {@inheritDoc}
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('emico_tweakwise/menu/subsection.phtml');
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getSubsection()->getTitle();
    }

    /**
     * @return Emico_Tweakwise_Block_Menu_NavItem[]
     */
    public function getItems()
    {
        if (!$this->hasData('items')) {
            $items = [];
            foreach ($this->getSubsection()->getNavItems() as $item) {
                $items[] = $this->createItemBlock($item, count($items));
            }
            $this->setData('items', $items);
        }

        return $this->getData('items');
    }

    /**
     * @param Emico_Tweakwise_Model_Bus_Type_Subsection $navItem
     * @param int $counter
     * @return Emico_Tweakwise_Block_Menu_MainSection
     */
    protected function createItemBlock(Emico_Tweakwise_Model_Bus_Type_NavItem $navItem, $counter)
    {
        /** @var Emico_Tweakwise_Block_Menu_NavItem $block */
        $block = $this->getLayout()->createBlock('emico_tweakwise/menu_navItem');
        $block->setNavItem($navItem);
        $block->setCounter($this->getCounter() . '-' . $counter);

        return $block;
    }
}