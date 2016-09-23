<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Format_SubMenuItem extends yucca_shopAdminPageFramework_Format_Base
{
    public static $aStructure = [];
    public $aSubMenuItem = [];
    public $oFactory;
    public $iParsedIndex = 1;

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->aSubMenuItem, $this->oFactory, $this->iParsedIndex];
        $this->aSubMenuItem = $_aParameters[0];
        $this->oFactory = $_aParameters[1];
        $this->iParsedIndex = $_aParameters[2];
    }

    public function get()
    {
        $_aSubMenuItem = $this->getAsArray($this->aSubMenuItem);
        if (isset($_aSubMenuItem['page_slug'])) {
            $_oFormatter = new yucca_shopAdminPageFramework_Format_SubMenuPage($_aSubMenuItem, $this->oFactory, $this->iParsedIndex);

            return $_oFormatter->get();
        }
        if (isset($_aSubMenuItem['href'])) {
            $_oFormatter = new yucca_shopAdminPageFramework_Format_SubMenuLink($_aSubMenuItem, $this->oFactory, $this->iParsedIndex);

            return $_oFormatter->get();
        }

        return [];
    }
}
