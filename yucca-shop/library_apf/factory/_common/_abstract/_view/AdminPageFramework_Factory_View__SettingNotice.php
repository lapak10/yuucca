<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Factory_View__SettingNotice extends yucca_shopAdminPageFramework_FrameworkUtility
{
    public $oFactory;

    public function __construct($oFactory, $sActionHookName = 'admin_notices')
    {
        $this->oFactory = $oFactory;
        add_action($sActionHookName, [$this, '_replyToPrintSettingNotice']);
    }

    public function _replyToPrintSettingNotice()
    {
        if (!$this->_shouldProceed()) {
            return;
        }
        $this->oFactory->oForm->printSubmitNotices();
    }

    private function _shouldProceed()
    {
        if (!$this->oFactory->_isInThePage()) {
            return false;
        }
        if ($this->hasBeenCalled(__METHOD__)) {
            return false;
        }

        return isset($this->oFactory->oForm);
    }
}
