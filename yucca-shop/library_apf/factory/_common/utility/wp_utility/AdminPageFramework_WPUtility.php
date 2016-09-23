<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_WPUtility_URL extends yucca_shopAdminPageFramework_Utility
{
    public static function getCurrentAdminURL()
    {
        $sRequestURI = $GLOBALS['is_IIS'] ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'];
        $sPageURL = 'on' == @$_SERVER['HTTPS'] ? 'https://' : 'http://';
        if ('80' != $_SERVER['SERVER_PORT']) {
            $sPageURL .= $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$sRequestURI;
        } else {
            $sPageURL .= $_SERVER['SERVER_NAME'].$sRequestURI;
        }

        return $sPageURL;
    }

    public static function getQueryAdminURL($aAddingQueries = [], $aRemovingQueryKeys = [], $sSubjectURL = '')
    {
        $_sAdminURL = is_network_admin() ? network_admin_url(yucca_shopAdminPageFramework_WPUtility_Page::getPageNow()) : admin_url(yucca_shopAdminPageFramework_WPUtility_Page::getPageNow());
        $sSubjectURL = $sSubjectURL ? $sSubjectURL : add_query_arg($_GET, $_sAdminURL);

        return self::getQueryURL($aAddingQueries, $aRemovingQueryKeys, $sSubjectURL);
    }

    public static function getQueryURL($aAddingQueries, $aRemovingQueryKeys, $sSubjectURL)
    {
        $sSubjectURL = empty($aRemovingQueryKeys) ? $sSubjectURL : remove_query_arg((array) $aRemovingQueryKeys, $sSubjectURL);
        $sSubjectURL = add_query_arg($aAddingQueries, $sSubjectURL);

        return $sSubjectURL;
    }

    public static function getSRCFromPath($sFilePath)
    {
        $_oWPStyles = new WP_Styles();
        $_sRelativePath = yucca_shopAdminPageFramework_Utility::getRelativePath(ABSPATH, $sFilePath);
        $_sRelativePath = preg_replace("/^\.[\/\\\]/", '', $_sRelativePath, 1);
        $_sHref = trailingslashit($_oWPStyles->base_url).$_sRelativePath;
        unset($_oWPStyles);

        return $_sHref;
    }

    public static function getResolvedSRC($sSRC, $bReturnNullIfNotExist = false)
    {
        if (!self::isResourcePath($sSRC)) {
            return $bReturnNullIfNotExist ? null : $sSRC;
        }
        if (filter_var($sSRC, FILTER_VALIDATE_URL)) {
            return $sSRC;
        }
        if (file_exists(realpath($sSRC))) {
            return self::getSRCFromPath($sSRC);
        }
        if ($bReturnNullIfNotExist) {
            return;
        }

        return $sSRC;
    }

    public static function resolveSRC($sSRC, $bReturnNullIfNotExist = false)
    {
        return self::getResolvedSRC($sSRC, $bReturnNullIfNotExist);
    }
}
class yucca_shopAdminPageFramework_WPUtility_HTML extends yucca_shopAdminPageFramework_WPUtility_URL
{
    public static function getAttributes(array $aAttributes)
    {
        $_sQuoteCharactor = "'";
        $_aOutput = [];
        foreach ($aAttributes as $_sAttribute => $_mProperty) {
            if (is_scalar($_mProperty)) {
                $_aOutput[] = "{$_sAttribute}={$_sQuoteCharactor}".esc_attr($_mProperty)."{$_sQuoteCharactor}";
            }
        }

        return implode(' ', $_aOutput);
    }

    public static function generateAttributes(array $aAttributes)
    {
        return self::getAttributes($aAttributes);
    }

    public static function getDataAttributes(array $aArray)
    {
        return self::getAttributes(self::getDataAttributeArray($aArray));
    }

    public static function generateDataAttributes(array $aArray)
    {
        return self::getDataAttributes($aArray);
    }

    public static function getHTMLTag($sTagName, array $aAttributes, $sValue = null)
    {
        $_sTag = tag_escape($sTagName);

        return null === $sValue ? '<'.$_sTag.' '.self::getAttributes($aAttributes).' />' : '<'.$_sTag.' '.self::getAttributes($aAttributes).'>'.$sValue."</{$_sTag}>";
    }

    public static function generateHTMLTag($sTagName, array $aAttributes, $sValue = null)
    {
        return self::getHTMLTag($sTagName, $aAttributes, $sValue);
    }
}
class yucca_shopAdminPageFramework_WPUtility_Page extends yucca_shopAdminPageFramework_WPUtility_HTML
{
    public static function getCurrentPostType()
    {
        if (isset(self::$_sCurrentPostType)) {
            return self::$_sCurrentPostType;
        }
        self::$_sCurrentPostType = self::_getCurrentPostType();

        return self::$_sCurrentPostType;
    }

    private static $_sCurrentPostType;

    private static function _getCurrentPostType()
    {
        $_aMethodsToTry = ['getPostTypeByTypeNow', 'getPostTypeByScreenObject', 'getPostTypeByREQUEST', 'getPostTypeByPostObject'];
        foreach ($_aMethodsToTry as $_sMethodName) {
            $_sPostType = call_user_func([__CLASS__, $_sMethodName]);
            if ($_sPostType) {
                return $_sPostType;
            }
        }
    }

    public static function getPostTypeByTypeNow()
    {
        if (isset($GLOBALS['typenow']) && $GLOBALS['typenow']) {
            return $GLOBALS['typenow'];
        }
    }

    public static function getPostTypeByScreenObject()
    {
        if (isset($GLOBALS['current_screen']->post_type) && $GLOBALS['current_screen']->post_type) {
            return $GLOBALS['current_screen']->post_type;
        }
    }

    public static function getPostTypeByREQUEST()
    {
        if (isset($_REQUEST['post_type'])) {
            return sanitize_key($_REQUEST['post_type']);
        }
        if (isset($_GET['post']) && $_GET['post']) {
            return get_post_type($_GET['post']);
        }
    }

    public static function getPostTypeByPostObject()
    {
        if (isset($GLOBALS['post']->post_type) && $GLOBALS['post']->post_type) {
            return $GLOBALS['post']->post_type;
        }
    }

    public static function isCustomTaxonomyPage($asPostTypes = [])
    {
        if (!in_array(self::getPageNow(), ['tags.php', 'edit-tags.php'])) {
            return false;
        }

        return self::isCurrentPostTypeIn($asPostTypes);
    }

    public static function isPostDefinitionPage($asPostTypes = [])
    {
        if (!in_array(self::getPageNow(), ['post.php', 'post-new.php'])) {
            return false;
        }

        return self::isCurrentPostTypeIn($asPostTypes);
    }

    public static function isCurrentPostTypeIn($asPostTypes)
    {
        $_aPostTypes = self::getAsArray($asPostTypes);
        if (empty($_aPostTypes)) {
            return true;
        }

        return in_array(self::getCurrentPostType(), $_aPostTypes);
    }

    public static function isPostListingPage($asPostTypes = [])
    {
        if ('edit.php' != self::getPageNow()) {
            return false;
        }
        $_aPostTypes = self::getAsArray($asPostTypes);
        if (!isset($_GET['post_type'])) {
            return in_array('post', $_aPostTypes);
        }

        return in_array($_GET['post_type'], $_aPostTypes);
    }

    private static $_sPageNow;

    public static function getPageNow()
    {
        if (isset(self::$_sPageNow)) {
            return self::$_sPageNow;
        }
        if (isset($GLOBALS['pagenow'])) {
            self::$_sPageNow = $GLOBALS['pagenow'];

            return self::$_sPageNow;
        }
        $_aMethodNames = [0 => '_getPageNow_FrontEnd', 1 => '_getPageNow_BackEnd'];
        $_sMethodName = $_aMethodNames[(int) is_admin()];
        self::$_sPageNow = self::$_sMethodName();

        return self::$_sPageNow;
    }

    private static function _getPageNow_FrontEnd()
    {
        if (preg_match('#([^/]+\.php)([?/].*?)?$#i', $_SERVER['PHP_SELF'], $_aMatches)) {
            return strtolower($_aMatches[1]);
        }

        return 'index.php';
    }

    private static function _getPageNow_BackEnd()
    {
        $_sPageNow = self::_getPageNowAdminURLBasePath();
        if (self::_isInAdminIndex($_sPageNow)) {
            return 'index.php';
        }
        preg_match('#(.*?)(/|$)#', $_sPageNow, $_aMatches);
        $_sPageNow = strtolower($_aMatches[1]);
        if ('.php' !== substr($_sPageNow, -4, 4)) {
            $_sPageNow .= '.php';
        }

        return $_sPageNow;
    }

    private static function _getPageNowAdminURLBasePath()
    {
        if (is_network_admin()) {
            $_sNeedle = '#/wp-admin/network/?(.*?)$#i';
        } elseif (is_user_admin()) {
            $_sNeedle = '#/wp-admin/user/?(.*?)$#i';
        } else {
            $_sNeedle = '#/wp-admin/?(.*?)$#i';
        }
        preg_match($_sNeedle, $_SERVER['PHP_SELF'], $_aMatches);

        return preg_replace('#\?.*?$#', '', trim($_aMatches[1], '/'));
    }

    private static function _isInAdminIndex($sPageNow)
    {
        return in_array($sPageNow, ['', 'index', 'index.php']);
    }

    public static function getCurrentScreenID()
    {
        $_oScreen = get_current_screen();
        if (is_string($_oScreen)) {
            $_oScreen = convert_to_screen($_oScreen);
        }
        if (isset($_oScreen->id)) {
            return $_oScreen->id;
        }
        if (isset($GLBOALS['page_hook'])) {
            return is_network_admin() ? $GLBOALS['page_hook'].'-network' : $GLBOALS['page_hook'];
        }

        return '';
    }

    public static function doesMetaBoxExist($sContext = '')
    {
        $_aDimensions = ['wp_meta_boxes', $GLOBALS['page_hook']];
        if ($sContext) {
            $_aDimensions[] = $sContext;
        }
        $_aSideMetaBoxes = self::getElementAsArray($GLOBALS, $_aDimensions);

        return count($_aSideMetaBoxes) > 0;
    }

    public static function getNumberOfScreenColumns()
    {
        return get_current_screen()->get_columns();
    }
}
class yucca_shopAdminPageFramework_WPUtility_Hook extends yucca_shopAdminPageFramework_WPUtility_Page
{
    public static function registerAction($sActionHook, $oCallable, $iPriority = 10)
    {
        if (did_action($sActionHook)) {
            return call_user_func_array($oCallable, []);
        }
        add_action($sActionHook, $oCallable, $iPriority);
    }

    public static function doActions($aActionHooks, $vArgs1 = null, $vArgs2 = null, $_and_more = null)
    {
        $aArgs = func_get_args();
        $aActionHooks = $aArgs[0];
        foreach ((array) $aActionHooks as $sActionHook) {
            $aArgs[0] = $sActionHook;
            call_user_func_array('do_action', $aArgs);
        }
    }

    public static function addAndDoActions()
    {
        $aArgs = func_get_args();
        $oCallerObject = $aArgs[0];
        $aActionHooks = $aArgs[1];
        foreach ((array) $aActionHooks as $sActionHook) {
            if (!$sActionHook) {
                continue;
            }
            $aArgs[1] = $sActionHook;
            call_user_func_array([get_class(), 'addAndDoAction'], $aArgs);
        }
    }

    public static function addAndDoAction()
    {
        $_iArgs = func_num_args();
        $_aArgs = func_get_args();
        $_oCallerObject = $_aArgs[0];
        $_sActionHook = $_aArgs[1];
        if (!$_sActionHook) {
            return;
        }
        $_sAutoCallbackMethodName = str_replace('\\', '_', $_sActionHook);
        if (method_exists($_oCallerObject, $_sAutoCallbackMethodName)) {
            add_action($_sActionHook, [$_oCallerObject, $_sAutoCallbackMethodName], 10, $_iArgs - 2);
        }
        array_shift($_aArgs);
        call_user_func_array('do_action', $_aArgs);
    }

    public static function addAndApplyFilters()
    {
        $_aArgs = func_get_args();
        $_aFilters = $_aArgs[1];
        $_vInput = $_aArgs[2];
        foreach ((array) $_aFilters as $_sFilter) {
            if (!$_sFilter) {
                continue;
            }
            $_aArgs[1] = $_sFilter;
            $_aArgs[2] = $_vInput;
            $_vInput = call_user_func_array([get_class(), 'addAndApplyFilter'], $_aArgs);
        }

        return $_vInput;
    }

    public static function addAndApplyFilter()
    {
        $_iArgs = func_num_args();
        $_aArgs = func_get_args();
        $_oCallerObject = $_aArgs[0];
        $_sFilter = $_aArgs[1];
        if (!$_sFilter) {
            return $_aArgs[2];
        }
        $_sAutoCallbackMethodName = str_replace('\\', '_', $_sFilter);
        if (method_exists($_oCallerObject, $_sAutoCallbackMethodName)) {
            add_filter($_sFilter, [$_oCallerObject, $_sAutoCallbackMethodName], 10, $_iArgs - 2);
        }
        array_shift($_aArgs);

        return call_user_func_array('apply_filters', $_aArgs);
    }

    public static function getFilterArrayByPrefix($sPrefix, $sClassName, $sPageSlug, $sTabSlug, $bReverse = false)
    {
        $_aFilters = [];
        if ($sTabSlug && $sPageSlug) {
            $_aFilters[] = "{$sPrefix}{$sPageSlug}_{$sTabSlug}";
        }
        if ($sPageSlug) {
            $_aFilters[] = "{$sPrefix}{$sPageSlug}";
        }
        if ($sClassName) {
            $_aFilters[] = "{$sPrefix}{$sClassName}";
        }

        return $bReverse ? array_reverse($_aFilters) : $_aFilters;
    }
}
class yucca_shopAdminPageFramework_WPUtility_File extends yucca_shopAdminPageFramework_WPUtility_Hook
{
    public static function getScriptData($sPathOrContent, $sType = 'plugin', $aDefaultHeaderKeys = [])
    {
        $_aHeaderKeys = $aDefaultHeaderKeys + ['sName' => 'Name', 'sURI' => 'URI', 'sScriptName' => 'Script Name', 'sLibraryName' => 'Library Name', 'sLibraryURI' => 'Library URI', 'sPluginName' => 'Plugin Name', 'sPluginURI' => 'Plugin URI', 'sThemeName' => 'Theme Name', 'sThemeURI' => 'Theme URI', 'sVersion' => 'Version', 'sDescription' => 'Description', 'sAuthor' => 'Author', 'sAuthorURI' => 'Author URI', 'sTextDomain' => 'Text Domain', 'sDomainPath' => 'Domain Path', 'sNetwork' => 'Network', '_sitewide' => 'Site Wide Only'];
        $aData = file_exists($sPathOrContent) ? get_file_data($sPathOrContent, $_aHeaderKeys, $sType) : self::getScriptDataFromContents($sPathOrContent, $sType, $_aHeaderKeys);
        switch (trim($sType)) {
            case 'theme':
                $aData['sName'] = $aData['sThemeName'];
                $aData['sURI'] = $aData['sThemeURI'];
            break;
            case 'library':
                $aData['sName'] = $aData['sLibraryName'];
                $aData['sURI'] = $aData['sLibraryURI'];
            break;
            case 'script':
                $aData['sName'] = $aData['sScriptName'];
            break;
            case 'plugin':
                $aData['sName'] = $aData['sPluginName'];
                $aData['sURI'] = $aData['sPluginURI'];
            break;
            default:
            break;
        }

        return $aData;
    }

    public static function getScriptDataFromContents($sContent, $sType = 'plugin', $aDefaultHeaderKeys = [])
    {
        $sContent = str_replace("\r", "\n", $sContent);
        $_aHeaders = $aDefaultHeaderKeys;
        if ($sType) {
            $_aExtraHeaders = apply_filters("extra_{$sType}_headers", []);
            if (!empty($_aExtraHeaders)) {
                $_aExtraHeaders = array_combine($_aExtraHeaders, $_aExtraHeaders);
                $_aHeaders = array_merge($_aExtraHeaders, (array) $aDefaultHeaderKeys);
            }
        }
        foreach ($_aHeaders as $_sHeaderKey => $_sRegex) {
            $_bFound = preg_match('/^[ \t\/*#@]*'.preg_quote($_sRegex, '/').':(.*)$/mi', $sContent, $_aMatch);
            $_aHeaders[$_sHeaderKey] = $_bFound && $_aMatch[1] ? _cleanup_header_comment($_aMatch[1]) : '';
        }

        return $_aHeaders;
    }

    public static function download($sURL, $iTimeOut = 300)
    {
        if (false === filter_var($sURL, FILTER_VALIDATE_URL)) {
            return false;
        }
        $_sTmpFileName = self::setTempPath(self::getBaseNameOfURL($sURL));
        if (!$_sTmpFileName) {
            return false;
        }
        $_aoResponse = wp_safe_remote_get($sURL, ['timeout' => $iTimeOut, 'stream' => true, 'filename' => $_sTmpFileName]);
        if (is_wp_error($_aoResponse)) {
            unlink($_sTmpFileName);

            return false;
        }
        if (200 != wp_remote_retrieve_response_code($_aoResponse)) {
            unlink($_sTmpFileName);

            return false;
        }
        $_sContent_md5 = wp_remote_retrieve_header($_aoResponse, 'content-md5');
        if ($_sContent_md5) {
            $_boIsMD5 = verify_file_md5($_sTmpFileName, $_sContent_md5);
            if (is_wp_error($_boIsMD5)) {
                unlink($_sTmpFileName);

                return false;
            }
        }

        return $_sTmpFileName;
    }

    public static function setTempPath($sFilePath = '')
    {
        $_sDir = get_temp_dir();
        $sFilePath = basename($sFilePath);
        if (empty($sFilePath)) {
            $sFilePath = time().'.tmp';
        }
        $sFilePath = $_sDir.wp_unique_filename($_sDir, $sFilePath);
        touch($sFilePath);

        return $sFilePath;
    }

    public static function getBaseNameOfURL($sURL)
    {
        $_sPath = parse_url($sURL, PHP_URL_PATH);
        $_sFileBaseName = basename($_sPath);

        return $_sFileBaseName;
    }
}
class yucca_shopAdminPageFramework_WPUtility_Option extends yucca_shopAdminPageFramework_WPUtility_File
{
    private static $_bIsNetworkAdmin;

    public static function deleteTransient($sTransientKey)
    {
        global $_wp_using_ext_object_cache;
        $_bWpUsingExtObjectCacheTemp = $_wp_using_ext_object_cache;
        $_wp_using_ext_object_cache = false;
        self::$_bIsNetworkAdmin = isset(self::$_bIsNetworkAdmin) ? self::$_bIsNetworkAdmin : is_network_admin();
        $sTransientKey = self::_getCompatibleTransientKey($sTransientKey, self::$_bIsNetworkAdmin ? 40 : 45);
        $_aFunctionNames = [0 => 'delete_transient', 1 => 'delete_site_transient'];
        $_vTransient = $_aFunctionNames[(int) self::$_bIsNetworkAdmin]($sTransientKey);
        $_wp_using_ext_object_cache = $_bWpUsingExtObjectCacheTemp;

        return $_vTransient;
    }

    public static function getTransient($sTransientKey, $vDefault = null)
    {
        global $_wp_using_ext_object_cache;
        $_bWpUsingExtObjectCacheTemp = $_wp_using_ext_object_cache;
        $_wp_using_ext_object_cache = false;
        self::$_bIsNetworkAdmin = isset(self::$_bIsNetworkAdmin) ? self::$_bIsNetworkAdmin : is_network_admin();
        $sTransientKey = self::_getCompatibleTransientKey($sTransientKey, self::$_bIsNetworkAdmin ? 40 : 45);
        $_aFunctionNames = [0 => 'get_transient', 1 => 'get_site_transient'];
        $_vTransient = $_aFunctionNames[(int) self::$_bIsNetworkAdmin]($sTransientKey);
        $_wp_using_ext_object_cache = $_bWpUsingExtObjectCacheTemp;

        return null === $vDefault ? $_vTransient : (false === $_vTransient ? $vDefault : $_vTransient);
    }

    public static function setTransient($sTransientKey, $vValue, $iExpiration = 0)
    {
        global $_wp_using_ext_object_cache;
        $_bWpUsingExtObjectCacheTemp = $_wp_using_ext_object_cache;
        $_wp_using_ext_object_cache = false;
        self::$_bIsNetworkAdmin = isset(self::$_bIsNetworkAdmin) ? self::$_bIsNetworkAdmin : is_network_admin();
        $sTransientKey = self::_getCompatibleTransientKey($sTransientKey, self::$_bIsNetworkAdmin ? 40 : 45);
        $_aFunctionNames = [0 => 'set_transient', 1 => 'set_site_transient'];
        $_bIsSet = $_aFunctionNames[(int) self::$_bIsNetworkAdmin]($sTransientKey, $vValue, $iExpiration);
        $_wp_using_ext_object_cache = $_bWpUsingExtObjectCacheTemp;

        return $_bIsSet;
    }

    public static function _getCompatibleTransientKey($sSubject, $iAllowedCharacterLength = 45)
    {
        if (strlen($sSubject) <= $iAllowedCharacterLength) {
            return $sSubject;
        }
        $_iPrefixLengthToKeep = $iAllowedCharacterLength - 33;
        $_sPrefixToKeep = substr($sSubject, 0, $_iPrefixLengthToKeep - 1);

        return $_sPrefixToKeep.'_'.md5($sSubject);
    }

    public static function getOption($sOptionKey, $asKey = null, $vDefault = null, array $aAdditionalOptions = [])
    {
        return self::_getOptionByFunctionName($sOptionKey, $asKey, $vDefault, $aAdditionalOptions);
    }

    public static function getSiteOption($sOptionKey, $asKey = null, $vDefault = null, array $aAdditionalOptions = [])
    {
        return self::_getOptionByFunctionName($sOptionKey, $asKey, $vDefault, $aAdditionalOptions, 'get_site_option');
    }

    private static function _getOptionByFunctionName($sOptionKey, $asKey = null, $vDefault = null, array $aAdditionalOptions = [], $sFunctionName = 'get_option')
    {
        if (!isset($asKey)) {
            $_aOptions = $sFunctionName($sOptionKey, isset($vDefault) ? $vDefault : []);

            return empty($aAdditionalOptions) ? $_aOptions : self::uniteArrays($_aOptions, $aAdditionalOptions);
        }

        return self::getArrayValueByArrayKeys(self::uniteArrays(self::getAsArray($sFunctionName($sOptionKey, []), true), $aAdditionalOptions), self::getAsArray($asKey, true), $vDefault);
    }
}
class yucca_shopAdminPageFramework_WPUtility_Meta extends yucca_shopAdminPageFramework_WPUtility_Option
{
    public static function getSavedPostMetaArray($iPostID, array $aKeys)
    {
        return self::getMetaDataByKeys($iPostID, $aKeys);
    }

    public static function getSavedUserMetaArray($iUserID, array $aKeys)
    {
        return self::getMetaDataByKeys($iUserID, $aKeys, 'user');
    }

    public static function getMetaDataByKeys($iObjectID, $aKeys, $sMetaType = 'post')
    {
        $_aSavedMeta = [];
        if (!$iObjectID) {
            return $_aSavedMeta;
        }
        $_aFunctionNames = ['post' => 'get_post_meta', 'user' => 'get_user_meta'];
        $_sFunctionName = self::getElement($_aFunctionNames, $sMetaType, 'get_post_meta');
        foreach ($aKeys as $_sKey) {
            $_aSavedMeta[$_sKey] = call_user_func_array($_sFunctionName, [$iObjectID, $_sKey, true]);
        }

        return $_aSavedMeta;
    }
}
class yucca_shopAdminPageFramework_WPUtility_SiteInformation extends yucca_shopAdminPageFramework_WPUtility_Meta
{
    public static function isDebugModeEnabled()
    {
        return (bool) defined('WP_DEBUG') && WP_DEBUG;
    }

    public static function isDebugLogEnabled()
    {
        return (bool) defined('WP_DEBUG_LOG') && WP_DEBUG_LOG;
    }

    public static function isDebugDisplayEnabled()
    {
        return (bool) defined('WP_DEBUG_DISPLAY') && WP_DEBUG_DISPLAY;
    }

    public static function getSiteLanguage($sDefault = 'en_US')
    {
        return defined('WPLANG') && WPLANG ? WPLANG : $sDefault;
    }
}
class yucca_shopAdminPageFramework_WPUtility_SystemInformation extends yucca_shopAdminPageFramework_WPUtility_SiteInformation
{
    private static $_aMySQLInfo;

    public static function getMySQLInfo()
    {
        if (isset(self::$_aMySQLInfo)) {
            return self::$_aMySQLInfo;
        }
        global $wpdb;
        $_aOutput = ['Version' => isset($wpdb->use_mysqli) && $wpdb->use_mysqli ? @mysqli_get_server_info($wpdb->dbh) : @mysql_get_server_info()];
        foreach ((array) $wpdb->get_results('SHOW VARIABLES', ARRAY_A) as $_iIndex => $_aItem) {
            $_aItem = array_values($_aItem);
            $_sKey = array_shift($_aItem);
            $_sValue = array_shift($_aItem);
            $_aOutput[$_sKey] = $_sValue;
        }
        self::$_aMySQLInfo = $_aOutput;

        return self::$_aMySQLInfo;
    }

    public static function getMySQLErrorLogPath()
    {
        $_aMySQLInfo = self::getMySQLInfo();

        return isset($_aMySQLInfo['log_error']) ? $_aMySQLInfo['log_error'] : '';
    }

    public static function getMySQLErrorLog($iLines = 1)
    {
        $_sLog = self::getFileTailContents(self::getMySQLErrorLogPath(), $iLines);

        return $_sLog ? $_sLog : '';
    }
}
class yucca_shopAdminPageFramework_WPUtility extends yucca_shopAdminPageFramework_WPUtility_SystemInformation
{
    public static function getPostTypeSubMenuSlug($sPostTypeSlug, $aPostTypeArguments)
    {
        $_sCustomMenuSlug = self::getShowInMenuPostTypeArgument($aPostTypeArguments);
        if (is_string($_sCustomMenuSlug)) {
            return $_sCustomMenuSlug;
        }

        return 'edit.php?post_type='.$sPostTypeSlug;
    }

    public static function getShowInMenuPostTypeArgument($aPostTypeArguments)
    {
        return self::getElement($aPostTypeArguments, 'show_in_menu', self::getElement($aPostTypeArguments, 'show_ui', self::getElement($aPostTypeArguments, 'public', false)));
    }

    public static function getWPAdminDirPath()
    {
        $_sWPAdminPath = str_replace(get_bloginfo('url').'/', ABSPATH, get_admin_url());

        return rtrim($_sWPAdminPath, '/');
    }

    public static function goToLocalURL($sURL, $oCallbackOnError = null)
    {
        self::redirectByType($sURL, 1, $oCallbackOnError);
    }

    public static function goToURL($sURL, $oCallbackOnError = null)
    {
        self::redirectByType($sURL, 0, $oCallbackOnError);
    }

    public static function redirectByType($sURL, $iType = 0, $oCallbackOnError = null)
    {
        $_iRedirectError = self::getRedirectPreError($sURL, $iType);
        if ($_iRedirectError && is_callable($oCallbackOnError)) {
            call_user_func_array($oCallbackOnError, [$_iRedirectError, $sURL]);

            return;
        }
        $_sFunctionName = [0 => 'wp_redirect', 1 => 'wp_safe_redirect'];
        exit($_sFunctionName[(int) $iType]($sURL));
    }

    public static function getRedirectPreError($sURL, $iType)
    {
        if (!$iType && filter_var($sURL, FILTER_VALIDATE_URL) === false) {
            return 1;
        }
        if (headers_sent()) {
            return 2;
        }

        return 0;
    }

    public static function isDebugMode()
    {
        return defined('WP_DEBUG') && WP_DEBUG;
    }

    public static function isDoingAjax()
    {
        return defined('DOING_AJAX') && DOING_AJAX;
    }

    public static function flushRewriteRules()
    {
        if (self::$_bIsFlushed) {
            return;
        }
        flush_rewrite_rules();
        self::$_bIsFlushed = true;
    }

    private static $_bIsFlushed = false;
}
