<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_View___CSS_FieldError extends yucca_shopAdminPageFramework_Form_View___CSS_Base
{
    protected function _get()
    {
        return $this->_getFieldErrorRules();
    }

    private function _getFieldErrorRules()
    {
        return '.field-error, .section-error{color: red;float: left;clear: both;margin-bottom: 0.5em;}.repeatable-section-error,.repeatable-field-error {float: right;clear: both;color: red;margin-left: 1em;}';
    }
}
