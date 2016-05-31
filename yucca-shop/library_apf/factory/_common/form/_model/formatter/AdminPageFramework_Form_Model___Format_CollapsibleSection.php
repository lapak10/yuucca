<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_Model___Format_CollapsibleSection extends yucca_shopAdminPageFramework_FrameworkUtility
{
    public static $aStructure = ['title' => null, 'is_collapsed' => true, 'toggle_all_button' => null, 'collapse_others_on_expand' => true, 'container' => 'sections', 'type' => 'box'];
    public $abCollapsible = false;
    public $sTitle = '';
    public $aSection = [];

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->abCollapsible, $this->sTitle, $this->aSection];
        $this->abCollapsible = $_aParameters[0];
        $this->sTitle = $_aParameters[1];
        $this->aSection = $_aParameters[2];
    }

    public function get()
    {
        if (empty($this->abCollapsible)) {
            return $this->abCollapsible;
        }

        return $this->_getArguments($this->abCollapsible, $this->sTitle, $this->aSection);
    }

    private function _getArguments($abCollapsible, $sTitle, array $aSection)
    {
        $_aCollapsible = $this->getAsArray($this->abCollapsible) + ['title' => $sTitle] + self::$aStructure;
        $_aCollapsible['toggle_all_button'] = implode(',', $this->getAsArray($_aCollapsible['toggle_all_button']));
        if (!empty($aSection)) {
            $_aCollapsible['toggle_all_button'] = $this->_getToggleAllButtonArgument($_aCollapsible['toggle_all_button'], $aSection);
        }

        return $_aCollapsible;
    }

    private function _getToggleAllButtonArgument($sToggleAll, array $aSection)
    {
        if (!$aSection['repeatable']) {
            return $sToggleAll;
        }
        if ($aSection['_is_first_index'] && $aSection['_is_last_index']) {
            return $sToggleAll;
        }
        if (!$aSection['_is_first_index'] && !$aSection['_is_last_index']) {
            return 0;
        }
        $_aToggleAll = $this->getAOrB(true === $sToggleAll || 1 === $sToggleAll, ['top-right', 'bottom-right'], explode(',', $sToggleAll));
        $_aToggleAll = $this->getAOrB($aSection['_is_first_index'], $this->dropElementByValue($_aToggleAll, [1, true, 0, false, 'bottom-right', 'bottom-left']), $_aToggleAll);
        $_aToggleAll = $this->getAOrB($aSection['_is_last_index'], $this->dropElementByValue($_aToggleAll, [1, true, 0, false, 'top-right', 'top-left']), $_aToggleAll);
        $_aToggleAll = $this->getAOrB(empty($_aToggleAll), [0], $_aToggleAll);

        return implode(',', $_aToggleAll);
    }
}
