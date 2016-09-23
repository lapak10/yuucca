<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_UserMeta_Model___UserMeta extends yucca_shopAdminPageFramework_FrameworkUtility
{
    public $iUserID = [];
    public $aFieldsets = [];

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->iUserID, $this->aFieldsets];
        $this->iUserID = $_aParameters[0];
        $this->aFieldsets = $_aParameters[1];
    }

    public function get()
    {
        if (!$this->iUserID) {
            return [];
        }

        return $this->_getSavedDataFromFieldsets($this->iUserID, $this->aFieldsets);
    }

    private function _getSavedDataFromFieldsets($iUserID, $aFieldsets)
    {
        $_aMetaKeys = array_keys(get_user_meta($iUserID));
        $_aMetaData = [];
        foreach ($aFieldsets as $_sSectionID => $_aFieldsets) {
            if ('_default' == $_sSectionID) {
                foreach ($_aFieldsets as $_aFieldset) {
                    if (!in_array($_aFieldset['field_id'], $_aMetaKeys)) {
                        continue;
                    }
                    $_aMetaData[$_aFieldset['field_id']] = get_user_meta($iUserID, $_aFieldset['field_id'], true);
                }
            }
            if (!in_array($_sSectionID, $_aMetaKeys)) {
                continue;
            }
            $_aMetaData[$_sSectionID] = get_user_meta($iUserID, $_sSectionID, true);
        }

        return $_aMetaData;
    }
}
