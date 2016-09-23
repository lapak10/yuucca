<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_View___CSS_widget extends yucca_shopAdminPageFramework_Form_View___CSS_Base
{
    protected function _get()
    {
        return $this->_getWidgetRules();
    }

    private function _getWidgetRules()
    {
        return ".widget .yucca-shop-section .form-table > tbody > tr > td,.widget .yucca-shop-section .form-table > tbody > tr > th{display: inline-block;width: 100%;padding: 0;float: right;clear: right; }.widget .yucca-shop-field,.widget .yucca-shop-input-label-container{width: 100%;}.widget .sortable .yucca-shop-field {padding: 4% 4.4% 3.2% 4.4%;width: 91.2%;}.widget .yucca-shop-field input {margin-bottom: 0.1em;margin-top: 0.1em;}.widget .yucca-shop-field input[type=text],.widget .yucca-shop-field textarea {width: 100%;} @media screen and ( max-width: 782px ) {.widget .yucca-shop-fields {width: 99.2%;}.widget .yucca-shop-field input[type='checkbox'], .widget .yucca-shop-field input[type='radio'] {margin-top: 0;}}";
    }

    protected function _getVersionSpecific()
    {
        $_sCSSRules = '';
        if (version_compare($GLOBALS['wp_version'], '3.8', '<')) {
            $_sCSSRules .= '.widget .yucca-shop-section table.mceLayout {table-layout: fixed;}';
        }
        if (version_compare($GLOBALS['wp_version'], '3.8', '>=')) {
            $_sCSSRules .= '.widget .yucca-shop-section .form-table th{font-size: 13px;font-weight: normal;margin-bottom: 0.2em;}.widget .yucca-shop-section .form-table {margin-top: 1em;}';
        }

        return $_sCSSRules;
    }
}
