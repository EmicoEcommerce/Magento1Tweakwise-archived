<?php
/**
 * @copyright (c) Emico 2014
 */

/**
 * Nav item (link) menu block
 * @method Emico_Tweakwise_Model_Bus_Type_NavItem getNavItem();
 * @method Emico_Tweakwise_Block_Menu_NavItem setNavItem(Emico_Tweakwise_Model_Bus_Type_NavItem $navItem);
 * @method string getCounter();
 * @method Emico_Tweakwise_Block_Menu_NavItem setCounter(string $count);
 */
class Emico_Tweakwise_Block_Menu_NavItem extends Mage_Core_Block_Template
{
    /**
     * {@inheritDoc}
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('emico_tweakwise/menu/navitem.phtml');
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getNavItem()->getTitle();
    }

    /**
     * @return string
     */
    public function getItemUrl()
    {
        return $this->getNavItem()->getUrl();
    }
}