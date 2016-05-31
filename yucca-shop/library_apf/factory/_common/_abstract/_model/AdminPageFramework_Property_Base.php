<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class yucca_shopAdminPageFramework_Property_Base extends yucca_shopAdminPageFramework_FrameworkUtility
{
    private static $_aStructure_CallerInfo = ['sPath' => null, 'sType' => null, 'sName' => null, 'sURI' => null, 'sVersion' => null, 'sThemeURI' => null, 'sScriptURI' => null, 'sAuthorURI' => null, 'sAuthor' => null, 'sDescription' => null];
    public static $_aLibraryData;
    public $_sPropertyType = '';
    public $oCaller;
    public $sCallerPath;
    public $sClassName;
    public $sClassHash;
    public $sScript = '';
    public $sStyle = '';
    public $sStyleIE = '';
    public $aFieldTypeDefinitions = [];
    public static $_sDefaultScript = '';
    public static $_sDefaultStyle = '';
    public static $_sDefaultStyleIE = '';
    public $aEnqueuingScripts = [];
    public $aEnqueuingStyles = [];
    public $aResourceAttributes = [];
    public $iEnqueuedScriptIndex = 0;
    public $iEnqueuedStyleIndex = 0;
    public $bIsAdmin;
    public $bIsMinifiedVersion;
    public $sCapability;
    public $sStructureType;
    public $sTextDomain;
    public $sPageNow;
    public $_bSetupLoaded;
    public $bIsAdminAjax;
    public $sLabelPluginSettingsLink = null;
    public $aFooterInfo = ['sLeft' => '__SCRIPT_CREDIT__', 'sRight' => '__FRAMEWORK_CREDIT__'];
    public $_sFormRegistrationHook = 'current_screen';
    public $aFormArguments = ['caller_id' => '', 'structure_type' => '', 'action_hook_form_registration' => ''];
    public $aFormCallbacks = ['hfID' => null, 'hfTagID' => null, 'hfName' => null, 'hfNameFlat' => null, 'hfInputName' => null, 'hfInputNameFlat' => null, 'hfClass' => null];
    public $sScriptType = 'unknown';
    public $sSettingNoticeActionHook = 'admin_notices';
    public $aHelpTabText = [];
    public $aHelpTabTextSide = [];
    public $sTitle = '';

    public function __construct($oCaller, $sCallerPath, $sClassName, $sCapability, $sTextDomain, $sStructureType)
    {
        $this->oCaller = $oCaller;
        $this->sCallerPath = $sCallerPath;
        $this->sClassName = $sClassName;
        $this->sCapability = empty($sCapability) ? 'manage_options' : $sCapability;
        $this->sTextDomain = empty($sTextDomain) ? 'yucca-shop' : $sTextDomain;
        $this->sStructureType = $sStructureType;
        $this->sPageNow = $this->getPageNow();
        $this->bIsAdmin = is_admin();
        $this->bIsAdminAjax = in_array($this->sPageNow, ['admin-ajax.php']);
        unset($this->sScriptType, $this->sClassHash);
        $this->_setGlobals();
    }

    private function _setGlobals()
    {
        if (!isset($GLOBALS['ayucca_shopAdminPageFramework'])) {
            $GLOBALS['ayucca_shopAdminPageFramework'] = ['aFieldFlags' => []];
        }
    }

    public function setFormProperties()
    {
        $this->aFormArguments = $this->getFormArguments();
        $this->aFormCallbacks = $this->getFormCallbacks();
    }

    public function getFormArguments()
    {
        return ['caller_id' => $this->sClassName, 'structure_type' => $this->_sPropertyType, 'action_hook_form_registration' => $this->_sFormRegistrationHook] + $this->aFormArguments;
    }

    public function getFormCallbacks()
    {
        return ['is_in_the_page' => [$this->oCaller, '_replyToDetermineWhetherToProcessFormRegistration'], 'load_fieldset_resource' => [$this->oCaller, '_replyToFieldsetResourceRegistration'], 'is_fieldset_registration_allowed' => null, 'capability' => [$this->oCaller, '_replyToGetCapabilityForForm'], 'saved_data' => [$this->oCaller, '_replyToGetSavedFormData'], 'section_head_output' => [$this->oCaller, '_replyToGetSectionHeaderOutput'], 'fieldset_output' => [$this->oCaller, '_replyToGetFieldOutput'], 'sectionset_before_output' => [$this->oCaller, '_replyToFormatSectionsetDefinition'], 'fieldset_before_output' => [$this->oCaller, '_replyToFormatFieldsetDefinition'], 'fieldset_after_formatting' => [$this->oCaller, '_replyToModifyFieldsetDefinition'], 'fieldsets_after_formatting' => [$this->oCaller, '_replyToModifyFieldsetsDefinitions'], 'is_sectionset_visible' => [$this->oCaller, '_replyToDetermineSectionsetVisibility'], 'is_fieldset_visible' => [$this->oCaller, '_replyToDetermineFieldsetVisibility'], 'secitonsets_before_registration' => [$this->oCaller, '_replyToModifySectionsets'], 'fieldsets_before_registration' => [$this->oCaller, '_replyToModifyFieldsets'], 'handle_form_data' => [$this->oCaller, '_replyToHandleSubmittedFormData'], 'hfID' => [$this->oCaller, '_replyToGetInputID'], 'hfTagID' => [$this->oCaller, '_replyToGetInputTagIDAttribute'], 'hfName' => [$this->oCaller, '_replyToGetFieldNameAttribute'], 'hfNameFlat' => [$this->oCaller, '_replyToGetFlatFieldName'], 'hfInputName' => [$this->oCaller, '_replyToGetInputNameAttribute'], 'hfInputNameFlat' => [$this->oCaller, '_replyToGetFlatInputName'], 'hfClass' => [$this->oCaller, '_replyToGetInputClassAttribute'], 'hfSectionName' => [$this->oCaller, '_replyToGetSectionName']] + $this->aFormCallbacks;
    }

    public static function _setLibraryData()
    {
        self::$_aLibraryData = ['sName' => yucca_shopAdminPageFramework_Registry::NAME, 'sURI' => yucca_shopAdminPageFramework_Registry::URI, 'sScriptName' => yucca_shopAdminPageFramework_Registry::NAME, 'sLibraryName' => yucca_shopAdminPageFramework_Registry::NAME, 'sLibraryURI' => yucca_shopAdminPageFramework_Registry::URI, 'sPluginName' => '', 'sPluginURI' => '', 'sThemeName' => '', 'sThemeURI' => '', 'sVersion' => yucca_shopAdminPageFramework_Registry::getVersion(), 'sDescription' => yucca_shopAdminPageFramework_Registry::DESCRIPTION, 'sAuthor' => yucca_shopAdminPageFramework_Registry::AUTHOR, 'sAuthorURI' => yucca_shopAdminPageFramework_Registry::AUTHOR_URI, 'sTextDomain' => yucca_shopAdminPageFramework_Registry::TEXT_DOMAIN, 'sDomainPath' => yucca_shopAdminPageFramework_Registry::TEXT_DOMAIN_PATH, 'sNetwork' => '', '_sitewide' => ''];

        return self::$_aLibraryData;
    }

    public static function _getLibraryData()
    {
        return isset(self::$_aLibraryData) ? self::$_aLibraryData : self::_setLibraryData();
    }

    protected function getCallerInfo($sCallerPath = '')
    {
        if (isset(self::$_aScriptDataCaches[$sCallerPath])) {
            return self::$_aScriptDataCaches[$sCallerPath];
        }
        $_aCallerInfo = self::$_aStructure_CallerInfo;
        $_aCallerInfo['sPath'] = $sCallerPath;
        $_aCallerInfo['sType'] = $this->_getCallerType($_aCallerInfo['sPath']);
        if ('unknown' == $_aCallerInfo['sType']) {
            self::$_aScriptDataCaches[$sCallerPath] = $_aCallerInfo;

            return $_aCallerInfo;
        }
        if ('plugin' == $_aCallerInfo['sType']) {
            self::$_aScriptDataCaches[$sCallerPath] = $this->getScriptData($_aCallerInfo['sPath'], $_aCallerInfo['sType']) + $_aCallerInfo;

            return self::$_aScriptDataCaches[$sCallerPath];
        }
        if ('theme' == $_aCallerInfo['sType']) {
            $_oTheme = wp_get_theme();
            self::$_aScriptDataCaches[$sCallerPath] = ['sName' => $_oTheme->Name, 'sVersion' => $_oTheme->Version, 'sThemeURI' => $_oTheme->get('ThemeURI'), 'sURI' => $_oTheme->get('ThemeURI'), 'sAuthorURI' => $_oTheme->get('AuthorURI'), 'sAuthor' => $_oTheme->get('Author')] + $_aCallerInfo;

            return self::$_aScriptDataCaches[$sCallerPath];
        }
        self::$_aScriptDataCaches[$sCallerPath] = [];

        return self::$_aScriptDataCaches[$sCallerPath];
    }

    private static $_aScriptDataCaches = [];

    protected function _getCallerType($sScriptPath)
    {
        if (isset(self::$_aCallerTypeCache[$sScriptPath])) {
            return self::$_aCallerTypeCache[$sScriptPath];
        }
        $sScriptPath = str_replace('\\', '/', $sScriptPath);
        if (false !== strpos($sScriptPath, '/themes/')) {
            self::$_aCallerTypeCache[$sScriptPath] = 'theme';

            return 'theme';
        }
        if (false !== strpos($sScriptPath, '/plugins/')) {
            self::$_aCallerTypeCache[$sScriptPath] = 'plugin';

            return 'plugin';
        }
        self::$_aCallerTypeCache[$sScriptPath] = 'unknown';

        return 'unknown';
    }

    private static $_aCallerTypeCache = [];

    protected function _getOptions()
    {
        return [];
    }

    public function __get($sName)
    {
        if ('aScriptInfo' === $sName) {
            $this->sCallerPath = $this->sCallerPath ? $this->sCallerPath : $this->getCallerScriptPath(__FILE__);
            $this->aScriptInfo = $this->getCallerInfo($this->sCallerPath);

            return $this->aScriptInfo;
        }
        if ('aOptions' === $sName) {
            $this->aOptions = $this->_getOptions();

            return $this->aOptions;
        }
        if ('sClassHash' === $sName) {
            $this->sClassHash = md5($this->sClassName);

            return $this->sClassHash;
        }
        if ('sScriptType' === $sName) {
            $this->sScriptType = $this->_getCallerType($this->sCallerPath);

            return $this->sScriptType;
        }
        if ('oUtil' === $sName) {
            $this->oUtil = new yucca_shopAdminPageFramework_WPUtility();

            return $this->oUtil;
        }
    }
}
