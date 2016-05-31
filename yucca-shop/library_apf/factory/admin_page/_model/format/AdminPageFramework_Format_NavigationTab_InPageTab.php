<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Format_NavigationTab_InPageTab extends yucca_shopAdminPageFramework_Format_Base
{
    public static $aStructure = [];
    public $aTab = [];
    public $aTabs = [];
    public $aArguments = [];
    public $oFactory = [];

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->aTab, self::$aStructure, $this->aTabs, $this->aArguments, $this->oFactory];
        $this->aTab = $_aParameters[0];
        self::$aStructure = $_aParameters[1];
        $this->aTabs = $_aParameters[2];
        $this->aArguments = $_aParameters[3];
        $this->oFactory = $_aParameters[4];
    }

    public function get()
    {
        $_aTab = $this->uniteArrays($this->aTab, ['capability' => 'manage_options', 'show_in_page_tab' => true]);
        if (!$this->_isEnabled($_aTab)) {
            return [];
        }
        $_sSlug = $this->_getSlug($_aTab);
        $_aTab = ['slug' => $_sSlug, 'title' => $this->aTabs[$_sSlug]['title'], 'href' => $_aTab['disabled'] ? null : esc_url($this->getElement($_aTab, 'url', $this->getQueryAdminURL(['page' => $this->aArguments['page_slug'], 'tab' => $_sSlug], $this->oFactory->oProp->aDisallowedQueryKeys)))] + $this->uniteArrays($_aTab, ['attributes' => ['data-tab-slug' => $_sSlug]], self::$aStructure);

        return $_aTab;
    }

    private function _isEnabled($aTab)
    {
        return !in_array(false, [(bool) current_user_can($aTab['capability']), (bool) $aTab['show_in_page_tab'], (bool) $aTab['if']]);
    }

    private function _getSlug($aTab)
    {
        return isset($aTab['parent_tab_slug'], $this->aTabs[$aTab['parent_tab_slug']]) ? $aTab['parent_tab_slug'] : $aTab['tab_slug'];
    }
}
