<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class yucca_shopAdminPageFramework_PageMetaBox_Router extends yucca_shopAdminPageFramework_MetaBox_View
{
    protected function _isInstantiatable()
    {
        if (isset($GLOBALS['pagenow']) && 'admin-ajax.php' === $GLOBALS['pagenow']) {
            return false;
        }

        return true;
    }

    public function _isInThePage()
    {
        if (!$this->oProp->bIsAdmin) {
            return false;
        }
        if (!isset($_GET['page'])) {
            return false;
        }
        if (array_key_exists($_GET['page'], $this->oProp->aPageSlugs)) {
            return true;
        }

        return in_array($_GET['page'], $this->oProp->aPageSlugs);
    }
}
abstract class yucca_shopAdminPageFramework_PageMetaBox_Model extends yucca_shopAdminPageFramework_PageMetaBox_Router
{
    protected static $_sStructureType = 'page_meta_box';

    public function __construct($sMetaBoxID, $sTitle, $asPageSlugs = [], $sContext = 'normal', $sPriority = 'default', $sCapability = 'manage_options', $sTextDomain = 'yucca-shop')
    {
        $this->oProp = new yucca_shopAdminPageFramework_Property_page_meta_box($this, get_class($this), $sCapability, $sTextDomain, self::$_sStructureType);
        $this->oProp->aPageSlugs = is_string($asPageSlugs) ? [$asPageSlugs] : $asPageSlugs;
        parent::__construct($sMetaBoxID, $sTitle, $asPageSlugs, $sContext, $sPriority, $sCapability, $sTextDomain);
    }

    public function _replyToSetUpValidationHooks($oScreen)
    {
        foreach ($this->oProp->aPageSlugs as $_sIndexOrPageSlug => $_asTabArrayOrPageSlug) {
            if (is_scalar($_asTabArrayOrPageSlug)) {
                $_sPageSlug = $_asTabArrayOrPageSlug;
                add_filter("validation_saved_options_without_dynamic_elements_{$_sPageSlug}", [$this, '_replyToFilterPageOptionsWODynamicElements'], 10, 2);
                add_filter("validation_{$_sPageSlug}", [$this, '_replyToValidateOptions'], 10, 4);
                add_filter("options_update_status_{$_sPageSlug}", [$this, '_replyToModifyOptionsUpdateStatus']);
                continue;
            }
            $_sPageSlug = $_sIndexOrPageSlug;
            $_aTabs = $_asTabArrayOrPageSlug;
            foreach ($_aTabs as $_sTabSlug) {
                add_filter("validation_{$_sPageSlug}_{$_sTabSlug}", [$this, '_replyToValidateOptions'], 10, 4);
                add_filter("validation_saved_options_without_dynamic_elements_{$_sPageSlug}_{$_sTabSlug}", [$this, '_replyToFilterPageOptionsWODynamicElements'], 10, 2);
                add_filter("options_update_status_{$_sPageSlug}_{$_sTabSlug}", [$this, '_replyToModifyOptionsUpdateStatus']);
            }
        }
    }

    public function _replyToRegisterMetaBoxes($sPageHook = '')
    {
        foreach ($this->oProp->aPageSlugs as $_sKey => $_asPage) {
            if (is_string($_asPage)) {
                $this->_registerMetaBox($_asPage);
                continue;
            }
            $this->_registerMetaBoxes($_sKey, $_asPage);
        }
    }

    private function _registerMetaBoxes($sPageSlug, $asPage)
    {
        foreach ($this->oUtil->getAsArray($asPage) as $_sTabSlug) {
            if (!$this->oProp->isCurrentTab($_sTabSlug)) {
                continue;
            }
            $this->_registerMetaBox($sPageSlug);
        }
    }

    private function _registerMetaBox($sPageSlug)
    {
        add_meta_box($this->oProp->sMetaBoxID, $this->oProp->sTitle, [$this, '_replyToPrintMetaBoxContents'], $this->oProp->_getScreenIDOfPage($sPageSlug), $this->oProp->sContext, $this->oProp->sPriority, null);
    }

    public function _replyToFilterPageOptions($aPageOptions)
    {
        return $aPageOptions;
    }

    public function _replyToFilterPageOptionsWODynamicElements($aOptionsWODynamicElements, $oFactory)
    {
        return $this->oForm->dropRepeatableElements($aOptionsWODynamicElements);
    }

    public function _replyToValidateOptions($aNewPageOptions, $aOldPageOptions, $oAdminPage, $aSubmitInfo)
    {
        $_aNewMetaBoxInputs = $this->oForm->getSubmittedData($_POST);
        $_aOldMetaBoxInputs = $this->oUtil->castArrayContents($this->oForm->getDataStructureFromAddedFieldsets(), $aOldPageOptions);
        $_aNewMetaBoxInputsRaw = $_aNewMetaBoxInputs;
        $_aNewMetaBoxInputs = call_user_func_array([$this, 'validate'], [$_aNewMetaBoxInputs, $_aOldMetaBoxInputs, $this, $aSubmitInfo]);
        $_aNewMetaBoxInputs = $this->oUtil->addAndApplyFilters($this, "validation_{$this->oProp->sClassName}", $_aNewMetaBoxInputs, $_aOldMetaBoxInputs, $this, $aSubmitInfo);
        if ($this->hasFieldError()) {
            $this->setLastInputs($_aNewMetaBoxInputsRaw);
        }

        return $this->oUtil->uniteArrays($_aNewMetaBoxInputs, $aNewPageOptions);
    }

    public function _replyToModifyOptionsUpdateStatus($aStatus)
    {
        if (!$this->hasFieldError()) {
            return $aStatus;
        }

        return ['field_errors' => true] + $this->oUtil->getAsArray($aStatus);
    }

    public function _replyToGetSavedFormData()
    {
        $_aPageOptions = $this->oUtil->addAndApplyFilter($this, 'options_'.$this->oProp->sClassName, $this->oProp->oAdminPage->oProp->aOptions);

        return $this->oUtil->castArrayContents($this->oForm->getDataStructureFromAddedFieldsets(), $_aPageOptions);
    }

    private function _getPageMetaBoxOptionsFromPageOptions(array $aPageOptions, array $aFieldsets)
    {
        $_aOptions = [];
        foreach ($aFieldsets as $_sSectionID => $_aFieldsets) {
            if ('_default' === $_sSectionID) {
                foreach ($_aFieldsets as $_aField) {
                    if (array_key_exists($_aField['field_id'], $aPageOptions)) {
                        $_aOptions[$_aField['field_id']] = $aPageOptions[$_aField['field_id']];
                    }
                }
            }
            if (array_key_exists($_sSectionID, $aPageOptions)) {
                $_aOptions[$_sSectionID] = $aPageOptions[$_sSectionID];
            }
        }

        return $_aOptions;
    }
}
abstract class yucca_shopAdminPageFramework_PageMetaBox_View extends yucca_shopAdminPageFramework_PageMetaBox_Model
{
}
abstract class yucca_shopAdminPageFramework_PageMetaBox_Controller extends yucca_shopAdminPageFramework_PageMetaBox_View
{
    public function enqueueStyles($aSRCs, $sPageSlug = '', $sTabSlug = '', $aCustomArgs = [])
    {
        if (method_exists($this->oResource, '_enqueueStyles')) {
            return $this->oResource->_enqueueStyles($aSRCs, $sPageSlug, $sTabSlug, $aCustomArgs);
        }
    }

    public function enqueueStyle($sSRC, $sPageSlug = '', $sTabSlug = '', $aCustomArgs = [])
    {
        if (method_exists($this->oResource, '_enqueueStyle')) {
            return $this->oResource->_enqueueStyle($sSRC, $sPageSlug, $sTabSlug, $aCustomArgs);
        }
    }

    public function enqueueScripts($aSRCs, $sPageSlug = '', $sTabSlug = '', $aCustomArgs = [])
    {
        if (method_exists($this->oResource, '_enqueueScripts')) {
            return $this->oResource->_enqueueScripts($sSRC, $sPageSlug, $sTabSlug, $aCustomArgs);
        }
    }

    public function enqueueScript($sSRC, $sPageSlug = '', $sTabSlug = '', $aCustomArgs = [])
    {
        if (method_exists($this->oResource, '_enqueueScript')) {
            return $this->oResource->_enqueueScript($sSRC, $sPageSlug, $sTabSlug, $aCustomArgs);
        }
    }
}
abstract class yucca_shopAdminPageFramework_PageMetaBox extends yucca_shopAdminPageFramework_PageMetaBox_Controller
{
    public function __construct($sMetaBoxID, $sTitle, $asPageSlugs = [], $sContext = 'normal', $sPriority = 'default', $sCapability = 'manage_options', $sTextDomain = 'yucca-shop')
    {
        if (empty($asPageSlugs)) {
            return;
        }
        if (!$this->_isInstantiatable()) {
            return;
        }
        parent::__construct($sMetaBoxID, $sTitle, $asPageSlugs, $sContext, $sPriority, $sCapability, $sTextDomain);
    }
}
