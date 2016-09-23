<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_FieldType_section_title extends yucca_shopAdminPageFramework_FieldType_text
{
    public $aFieldTypeSlugs = ['section_title'];
    protected $aDefaultKeys = ['label_min_width' => 30, 'attributes' => ['size' => 20, 'maxlength' => 100]];

    protected function getStyles()
    {
        return '.yucca-shop-section-tab .yucca-shop-field-section_title {padding: 0.5em;} .yucca-shop-section-tab .yucca-shop-field-section_title .yucca-shop-input-label-string { vertical-align: middle; margin-left: 0.2em;}.yucca-shop-section-tab .yucca-shop-fields {display: inline-block;} .yucca-shop-field.yucca-shop-field-section_title {float: none;} .yucca-shop-field.yucca-shop-field-section_title input {background-color: #fff;color: #333;border-color: #ddd;box-shadow: inset 0 1px 2px rgba(0,0,0,.07);border-width: 1px;border-style: solid;outline: 0;box-sizing: border-box;vertical-align: middle;}';
    }

    protected function getField($aField)
    {
        $aField['attributes'] = ['type' => 'text'] + $aField['attributes'];

        return parent::getField($aField);
    }
}
