<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_FieldType_number extends yucca_shopAdminPageFramework_FieldType_text
{
    public $aFieldTypeSlugs = ['number', 'range'];
    protected $aDefaultKeys = ['attributes' => ['size' => 30, 'maxlength' => 400, 'class' => null, 'min' => null, 'max' => null, 'step' => null, 'readonly' => null, 'required' => null, 'placeholder' => null, 'list' => null, 'autofocus' => null, 'autocomplete' => null]];

    protected function getStyles()
    {
        return '';
    }
}
