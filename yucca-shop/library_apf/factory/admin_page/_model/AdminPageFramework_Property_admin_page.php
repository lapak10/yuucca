<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Property_admin_page extends yucca_shopAdminPageFramework_Property_Base
{
    public $_sPropertyType = 'admin_page';
    public $sStructureType = 'admin_page';
    public $sClassName;
    public $sClassHash;
    public $sCapability = 'manage_options';
    public $sPageHeadingTabTag = 'h2';
    public $sInPageTabTag = 'h3';
    public $sDefaultPageSlug;
    public $aPages = [];
    public $aHiddenPages = [];
    public $aRegisteredSubMenuPages = [];
    public $aRootMenu = ['sTitle' => null, 'sPageSlug' => null, 'sIcon16x16' => null, 'iPosition' => null, 'fCreateRoot' => null];
    public $aInPageTabs = [];
    public $aDefaultInPageTabs = [];
    public $aPluginDescriptionLinks = [];
    public $aPluginTitleLinks = [];
    public $sOptionKey = '';
    public $aHelpTabs = [];
    public $sFormEncType = 'multipart/form-data';
    public $sThickBoxButtonUseThis = '';
    public $bEnableForm = false;
    public $bShowPageTitle = true;
    public $bShowPageHeadingTabs = true;
    public $bShowInPageTabs = true;
    public $aAdminNotices = [];
    public $aDisallowedQueryKeys = ['settings-updated', 'confirmation', 'field_errors'];
    public $sTargetFormPage = '';
    public $_bDisableSavingOptions = false;
    public $aPageHooks = [];
    public $sWrapperClassAttribute = 'wrap';
    public $sOptionType = 'options_table';
    public $iOptionTransientDuration = 0;

    public function __construct($oCaller, $sCallerPath, $sClassName, $aisOptionKey, $sCapability = 'manage_options', $sTextDomain = 'yucca-shop')
    {
        $this->_sFormRegistrationHook = 'load_after_'.$sClassName;
        parent::__construct($oCaller, $sCallerPath, $sClassName, $sCapability, $sTextDomain, $this->sStructureType);
        $this->sTargetFormPage = $_SERVER['REQUEST_URI'];
        $this->_setOptionsProperties($aisOptionKey, $sClassName);
        $GLOBALS['ayucca_shopAdminPageFramework']['aPageClasses'] = $this->getElementAsArray($GLOBALS, ['ayucca_shopAdminPageFramework', 'aPageClasses']);
        $GLOBALS['ayucca_shopAdminPageFramework']['aPageClasses'][$sClassName] = $oCaller;
        add_filter("option_page_capability_{$this->sOptionKey}", [$this, '_replyToGetCapability']);
    }

    private function _setOptionsProperties($aisOptionKey, $sClassName)
    {
        $_aArguments = is_array($aisOptionKey) ? $aisOptionKey : [];
        $_aArguments = $_aArguments + ['type' => $this->_getOptionType($aisOptionKey), 'key' => $this->_getOptionKey($aisOptionKey, $sClassName), 'duration' => is_int($aisOptionKey) ? $aisOptionKey : 0];
        $this->sOptionKey = $_aArguments['key'];
        $this->sOptionType = $_aArguments['type'];
        $this->iOptionTransientDuration = $_aArguments['duration'];
        $this->_bDisableSavingOptions = '' === $aisOptionKey;
    }

    private function _getOptionKey($aisOptionKey, $sClassName)
    {
        $_sType = gettype($aisOptionKey);
        if (in_array($_sType, ['NULL', 'string'])) {
            return $aisOptionKey ? $aisOptionKey : $sClassName;
        }
        if (in_array($_sType, ['integer'])) {
            return 'apf_'.$sClassName.'_'.get_current_user_id();
        }

        return $aisOptionKey;
    }

    private function _getOptionType($aisOptionKey)
    {
        return is_int($aisOptionKey) ? 'transient' : 'options_table';
    }

    protected function _isAdminPage()
    {
        if (!is_admin()) {
            return false;
        }

        return isset($_GET['page']);
    }

    protected function _getOptions()
    {
        return $this->_getOptionsByType($this->sOptionType);
    }

    private function _getOptionsByType($sOptionType)
    {
        switch ($sOptionType) {
            default:
            case 'options_table':
                return $this->sOptionKey ? $this->getAsArray(get_option($this->sOptionKey, [])) : [];
            case 'transient':
                return $this->getAsArray($this->getTransient($this->sOptionKey, []));
        }
    }

    public function updateOption($aOptions = null)
    {
        if ($this->_bDisableSavingOptions) {
            return false;
        }

        return $this->_updateOptionsByType(null !== $aOptions ? $aOptions : $this->aOptions, $this->sOptionType);
    }

    private function _updateOptionsByType($aOptions, $sOptionType)
    {
        switch ($sOptionType) {
            default:
            case 'options_table':
                return update_option($this->sOptionKey, $aOptions);
            case 'transient':
                return $this->setTransient($this->sOptionKey, $aOptions, $this->iOptionTransientDuration);
        }
    }

    public function isPageAdded($sPageSlug = '')
    {
        $sPageSlug = trim($sPageSlug);
        $sPageSlug = $sPageSlug ? $sPageSlug : $this->getCurrentPageSlug();

        return isset($this->aPages[$sPageSlug]);
    }

    public function getCurrentPageSlug()
    {
        return $this->getElement($_GET, 'page', '');
    }

    public function getCurrentTabSlug($sCurrentPageSlug = '')
    {
        $_sTabSlug = $this->getElement($_GET, 'tab');
        if ($_sTabSlug) {
            return $_sTabSlug;
        }
        $sCurrentPageSlug = $sCurrentPageSlug ? $sCurrentPageSlug : $this->getCurrentPageSlug();

        return $sCurrentPageSlug ? $this->getDefaultInPageTab($sCurrentPageSlug) : '';
    }

    public function getCurrentTab($sCurrentPageSlug = '')
    {
        return $this->getCurrentTabSlug($sCurrentPageSlug);
    }

    public function getDefaultInPageTab($sPageSlug)
    {
        if (!$sPageSlug) {
            return '';
        }

        return $this->getElement($this->aDefaultInPageTabs, $sPageSlug, '');
    }

    public function _replyToGetCapability()
    {
        return $this->sCapability;
    }

    public function getCurrentPageSlugIfAdded()
    {
        static $_nsCurrentPageSlugFromAddedOnes;
        if ($this->hasBeenCalled(__METHOD__)) {
            return $_nsCurrentPageSlugFromAddedOnes;
        }
        $_nsCurrentPageSlug = $this->getElement($_GET, 'page', null);
        $_nsCurrentPageSlugFromAddedOnes = $this->getElement($this->aPages, [$_nsCurrentPageSlug, 'page_slug']);

        return $_nsCurrentPageSlugFromAddedOnes;
    }

    public function getCurrentInPageTabSlugIfAdded()
    {
        static $_nsCurrentTabSlugFromAddedOnes;
        if ($this->hasBeenCalled(__METHOD__)) {
            return $_nsCurrentTabSlugFromAddedOnes;
        }
        $_nsCurrentTabSlugFromAddedOnes = $this->getElement($this->aInPageTabs, [$this->getCurrentPageSlugIfAdded(), $this->getCurrentTabSlug(), 'tab_slug']);

        return $_nsCurrentTabSlugFromAddedOnes;
    }
}
