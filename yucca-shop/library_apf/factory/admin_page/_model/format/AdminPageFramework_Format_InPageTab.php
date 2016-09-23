<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Format_InPageTab extends yucca_shopAdminPageFramework_Format_Base
{
    public static $aStructure = ['page_slug' => null, 'tab_slug' => null, 'title' => null, 'order' => 10, 'show_in_page_tab' => true, 'parent_tab_slug' => null, 'url' => null, 'disabled' => null, 'attributes' => null, 'capability' => null, 'if' => true];
    public $aInPageTab = [];
    public $sPageSlug = '';
    public $oFactory;

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->aInPageTab, $this->sPageSlug, $this->oFactory];
        $this->aInPageTab = $_aParameters[0];
        $this->sPageSlug = $_aParameters[1];
        $this->oFactory = $_aParameters[2];
    }

    public function get()
    {
        return ['page_slug' => $this->sPageSlug] + $this->aInPageTab + ['capability' => $this->_getPageCapability()] + self::$aStructure;
    }

    private function _getPageCapability()
    {
        return $this->getElement($this->oFactory->oProp->aPages, [$this->sPageSlug, 'capability'], $this->oFactory->oProp->sCapability);
    }
}
