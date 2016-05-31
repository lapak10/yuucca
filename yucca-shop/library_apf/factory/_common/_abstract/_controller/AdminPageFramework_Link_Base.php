<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class yucca_shopAdminPageFramework_Link_Base extends yucca_shopAdminPageFramework_FrameworkUtility
{
    public $oProp;
    public $oMsg;

    public function __construct($oProp, $oMsg = null)
    {
        if (!$this->_isLoadable($oProp)) {
            return;
        }
        $this->oProp = $oProp;
        $this->oMsg = $oMsg;
        add_action('in_admin_footer', [$this, '_replyToSetFooterInfo']);
        if ('plugins.php' === $this->oProp->sPageNow && 'plugin' === $this->oProp->aScriptInfo['sType']) {
            add_filter('plugin_action_links_'.plugin_basename($this->oProp->aScriptInfo['sPath']), [$this, '_replyToAddSettingsLinkInPluginListingPage'], 20);
        }
    }

    private function _isLoadable($oProp)
    {
        if (!$oProp->bIsAdmin) {
            return false;
        }
        if ($oProp->bIsAdminAjax) {
            return false;
        }

        return !$this->hasBeenCalled('links_'.$oProp->sClassName);
    }

    public function _replyToSetFooterInfo()
    {
        $this->_setDefaultFooterText();
        $this->_setFooterHooks();
    }

    protected function _setDefaultFooterText()
    {
        $this->oProp->aFooterInfo['sLeft'] = str_replace('__SCRIPT_CREDIT__', $this->_getFooterInfoLeft($this->oProp->aScriptInfo), $this->oProp->aFooterInfo['sLeft']);
        $this->oProp->aFooterInfo['sRight'] = str_replace('__FRAMEWORK_CREDIT__', $this->_getFooterInfoRight($this->oProp->_getLibraryData()), $this->oProp->aFooterInfo['sRight']);
    }

    private function _getFooterInfoLeft($aScriptInfo)
    {
        $_sDescription = $this->getAOrB(empty($aScriptInfo['sDescription']), '', "&#13;{$aScriptInfo['sDescription']}");
        $_sVersion = $this->getAOrB(empty($aScriptInfo['sVersion']), '', "&nbsp;{$aScriptInfo['sVersion']}");
        $_sPluginInfo = $this->getAOrB(empty($aScriptInfo['sURI']), $aScriptInfo['sName'], $this->getHTMLTag('a', ['href' => $aScriptInfo['sURI'], 'target' => '_blank', 'title' => $aScriptInfo['sName'].$_sVersion.$_sDescription], $aScriptInfo['sName']));
        $_sAuthorInfo = $this->getAOrB(empty($aScriptInfo['sAuthorURI']), '', $this->getHTMLTag('a', ['href' => $aScriptInfo['sAuthorURI'], 'target' => '_blank', 'title' => $aScriptInfo['sAuthor']], $aScriptInfo['sAuthor']));
        $_sAuthorInfo = $this->getAOrB(empty($aScriptInfo['sAuthor']), $_sAuthorInfo, ' by '.$_sAuthorInfo);

        return "<span class='apf-script-info'>".$_sPluginInfo.$_sAuthorInfo.'</span>';
    }

    private function _getFooterInfoRight($aScriptInfo)
    {
        $_sDescription = $this->getAOrB(empty($aScriptInfo['sDescription']), '', "&#13;{$aScriptInfo['sDescription']}");
        $_sVersion = $this->getAOrB(empty($aScriptInfo['sVersion']), '', "&nbsp;{$aScriptInfo['sVersion']}");
        $_sLibraryInfo = $this->getAOrB(empty($aScriptInfo['sURI']), $aScriptInfo['sName'], $this->getHTMLTag('a', ['href' => $aScriptInfo['sURI'], 'target' => '_blank', 'title' => $aScriptInfo['sName'].$_sVersion.$_sDescription], $aScriptInfo['sName']));

        return "<span class='apf-credit' id='footer-thankyou'>".$this->oMsg->get('powered_by').'&nbsp;'.$_sLibraryInfo.',&nbsp;'.$this->oMsg->get('and').'&nbsp;'.$this->getHTMLTag('a', ['href' => 'https://wordpress.org', 'target' => '_blank', 'title' => 'WordPress '.$GLOBALS['wp_version']], 'WordPress').'</span>';
    }

    protected function _setFooterHooks()
    {
        add_filter('admin_footer_text', [$this, '_replyToAddInfoInFooterLeft']);
        add_filter('update_footer', [$this, '_replyToAddInfoInFooterRight'], 11);
    }

    public function _replyToAddInfoInFooterLeft($sLinkHTML = '')
    {
        $sLinkHTML = empty($this->oProp->aScriptInfo['sName']) ? $sLinkHTML : $this->oProp->aFooterInfo['sLeft'];

        return $this->addAndApplyFilters($this->oProp->oCaller, 'footer_left_'.$this->oProp->sClassName, $sLinkHTML);
    }

    public function _replyToAddInfoInFooterRight($sLinkHTML = '')
    {
        return $this->addAndApplyFilters($this->oProp->oCaller, 'footer_right_'.$this->oProp->sClassName, $this->oProp->aFooterInfo['sRight']);
    }
}
