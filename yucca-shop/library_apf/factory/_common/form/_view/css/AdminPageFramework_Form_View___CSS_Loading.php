<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_View___CSS_Loading extends yucca_shopAdminPageFramework_Form_View___CSS_Base
{
    protected function _get()
    {
        $_sSpinnerPath = $this->getWPAdminDirPath().'/images/wpspin_light-2x.gif';
        if (!file_exists($_sSpinnerPath)) {
            return '';
        }
        $_sSpinnerURL = esc_url(admin_url('/images/wpspin_light-2x.gif'));

        return ".yucca-shop-form-loading {position: absolute;background-image: url({$_sSpinnerURL});background-repeat: no-repeat;background-size: 32px 32px;background-position: center; display: block !important;width: 92%;height: 70%;opacity: 0.5;}";
    }
}
