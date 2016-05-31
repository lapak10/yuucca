<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_MetaBox_Model___PostMeta extends yucca_shopAdminPageFramework_FrameworkUtility
{
    public $iPostID = [];
    public $aFieldsets = [];

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->iPostID, $this->aFieldsets];
        $this->iPostID = $_aParameters[0];
        $this->aFieldsets = $_aParameters[1];
    }

    public function get()
    {
        if (!$this->iPostID) {
            return [];
        }

        return $this->_getSavedDataFromFieldsets($this->iPostID, $this->aFieldsets);
    }

    private function _getSavedDataFromFieldsets($iPostID, $aFieldsets)
    {
        $_aMetaKeys = $this->getAsArray(get_post_custom_keys($iPostID));
        $_aMetaData = [];
        foreach ($aFieldsets as $_sSectionID => $_aFieldsets) {
            if ('_default' == $_sSectionID) {
                foreach ($_aFieldsets as $_aFieldset) {
                    if (!in_array($_aFieldset['field_id'], $_aMetaKeys)) {
                        continue;
                    }
                    $_aMetaData[$_aFieldset['field_id']] = get_post_meta($iPostID, $_aFieldset['field_id'], true);
                }
            }
            if (!in_array($_sSectionID, $_aMetaKeys)) {
                continue;
            }
            $_aMetaData[$_sSectionID] = get_post_meta($iPostID, $_sSectionID, true);
        }

        return $_aMetaData;
    }
}
