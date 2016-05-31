<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Format_InPageTabs extends yucca_shopAdminPageFramework_Format_Base
{
    public static $aStructure = [];
    public $aInPageTabs = [];
    public $sPageSlug = '';
    public $oFactory;

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->aInPageTabs, $this->sPageSlug, $this->oFactory];
        $this->aInPageTabs = $_aParameters[0];
        $this->sPageSlug = $_aParameters[1];
        $this->oFactory = $_aParameters[2];
    }

    public function get()
    {
        $_aInPageTabs = $this->addAndApplyFilter($this->oFactory, "tabs_{$this->oFactory->oProp->sClassName}_{$this->sPageSlug}", $this->aInPageTabs);
        foreach ((array) $_aInPageTabs as $_sTabSlug => $_aInPageTab) {
            if (!is_array($_aInPageTab)) {
                continue;
            }
            $_oFormatter = new yucca_shopAdminPageFramework_Format_InPageTab($_aInPageTab, $this->sPageSlug, $this->oFactory);
            $_aInPageTabs[$_sTabSlug] = $_oFormatter->get();
        }
        uasort($_aInPageTabs, [$this, 'sortArrayByKey']);

        return $_aInPageTabs;
    }
}
