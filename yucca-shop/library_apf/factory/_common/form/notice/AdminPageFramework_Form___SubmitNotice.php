<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form___SubmitNotice extends yucca_shopAdminPageFramework_FrameworkUtility
{
    private static $_aNotices = [];
    public $sTransientKey;

    public function __construct()
    {
        $this->sTransientKey = $this->_getTransientKey();
    }

    private function _getTransientKey()
    {
        return 'apf_ntc_'.get_current_user_id();
    }

    public function hasNotice($sType = '')
    {
        if (!$sType) {
            return (bool) count(self::$_aNotices);
        }
        foreach (self::$_aNotices as $_aNotice) {
            $_sClassAttribute = $this->getElement($_aNotice, ['aAttributes', 'class'], '');
            if ($_sClassAttribute === $sType) {
                return true;
            }
        }

        return false;
    }

    public function set($sMessage, $sType = 'error', $asAttributes = [], $bOverride = true)
    {
        if (empty(self::$_aNotices)) {
            add_action('shutdown', [$this, '_replyToSaveNotices']);
        }
        $_sID = md5(trim($sMessage));
        if (!$bOverride && isset(self::$_aNotices[$_sID])) {
            return;
        }
        if ($bOverride) {
            self::$_aNotices = [];
        }
        $_aAttributes = $this->getAsArray($asAttributes);
        if (is_string($asAttributes) && !empty($asAttributes)) {
            $_aAttributes['id'] = $asAttributes;
        }
        self::$_aNotices[$_sID] = ['sMessage' => $sMessage, 'aAttributes' => $_aAttributes + ['class' => $sType, 'id' => 'form_submit_notice_'.$_sID]];
    }

    public function _replyToSaveNotices()
    {
        if (empty(self::$_aNotices)) {
            return;
        }
        $_bResult = $this->setTransient($this->sTransientKey, self::$_aNotices);
    }

    public function render()
    {
        new yucca_shopAdminPageFramework_AdminNotice('');
        $_aNotices = $this->_getNotices();
        if (false === $_aNotices) {
            return;
        }
        if (isset($_GET['settings-notice']) && !$_GET['settings-notice']) {
            return;
        }
        $this->_printNotices($_aNotices);
    }

    private function _getNotices()
    {
        if (isset(self::$_aNoticeCaches[$this->sTransientKey])) {
            return self::$_aNoticeCaches[$this->sTransientKey];
        }
        $_abNotices = $this->getTransient($this->sTransientKey);
        if (false !== $_abNotices) {
            $this->deleteTransient($this->sTransientKey);
        }
        self::$_aNoticeCaches[$this->sTransientKey] = $_abNotices;

        return self::$_aNoticeCaches[$this->sTransientKey];
    }

    private static $_aNoticeCaches = [];

    private function _printNotices($aNotices)
    {
        $_aPeventDuplicates = [];
        foreach (array_filter((array) $aNotices, 'is_array') as $_aNotice) {
            $_sNotificationKey = md5(serialize($_aNotice));
            if (isset($_aPeventDuplicates[$_sNotificationKey])) {
                continue;
            }
            $_aPeventDuplicates[$_sNotificationKey] = true;
            new yucca_shopAdminPageFramework_AdminNotice($this->getElement($_aNotice, 'sMessage'), $this->getElement($_aNotice, 'aAttributes'));
        }
    }
}
