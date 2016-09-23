<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Property_NetworkAdmin extends yucca_shopAdminPageFramework_Property_admin_page
{
    public $_sPropertyType = 'network_admin_page';
    public $sStructureType = 'network_admin_page';
    public $sSettingNoticeActionHook = 'network_admin_notices';

    protected function _getOptions()
    {
        return $this->addAndApplyFilter($this->getElement($GLOBALS, ['ayucca_shopAdminPageFramework', 'aPageClasses', $this->sClassName]), 'options_'.$this->sClassName, $this->sOptionKey ? get_site_option($this->sOptionKey, []) : []);
    }

    public function updateOption($aOptions = null)
    {
        if ($this->_bDisableSavingOptions) {
            return;
        }

        return update_site_option($this->sOptionKey, $aOptions !== null ? $aOptions : $this->aOptions);
    }
}
