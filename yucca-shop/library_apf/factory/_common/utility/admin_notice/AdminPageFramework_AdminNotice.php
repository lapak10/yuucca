<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_AdminNotice extends yucca_shopAdminPageFramework_FrameworkUtility
{
    private static $_aNotices = [];
    public $sNotice = '';
    public $aAttributes = [];
    public $aCallbacks = ['should_show' => null];

    public function __construct($sNotice, array $aAttributes = ['class' => 'error'], array $aCallbacks = [])
    {
        $this->aAttributes = $aAttributes + ['class' => 'error'];
        $this->aAttributes['class'] = $this->getClassAttribute($this->aAttributes['class'], 'yucca-shop-settings-notice-message', 'yucca-shop-settings-notice-container', 'notice', 'is-dismissible');
        $this->aCallbacks = $aCallbacks + $this->aCallbacks;
        new yucca_shopAdminPageFramework_AdminNotice___Script();
        if (!$sNotice) {
            return;
        }
        $this->sNotice = $sNotice;
        self::$_aNotices[$sNotice] = $sNotice;
        $this->registerAction('admin_notices', [$this, '_replyToDisplayAdminNotice']);
        $this->registerAction('network_admin_notices', [$this, '_replyToDisplayAdminNotice']);
    }

    public function _replyToDisplayAdminNotice()
    {
        if (!$this->_shouldProceed()) {
            return;
        }
        $_aAttributes = $this->aAttributes + ['style' => ''];
        $_aAttributes['style'] = $this->getStyleAttribute($_aAttributes['style'], 'display: none');
        echo '<div '.$this->getAttributes($_aAttributes).'>'.'<p>'.self::$_aNotices[$this->sNotice].'</p>'.'</div>'.'<noscript>'.'<div '.$this->getAttributes($this->aAttributes).'>'.'<p>'.self::$_aNotices[$this->sNotice].'</p>'.'</div>'.'</noscript>';
        unset(self::$_aNotices[$this->sNotice]);
    }

    private function _shouldProceed()
    {
        if (!is_callable($this->aCallbacks['should_show'])) {
            return true;
        }

        return call_user_func_array($this->aCallbacks['should_show'], [true]);
    }
}
