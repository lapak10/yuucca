<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_FieldType_hidden extends yucca_shopAdminPageFramework_FieldType
{
    public $aFieldTypeSlugs = ['hidden'];
    protected $aDefaultKeys = [];

    protected function getField($aField)
    {
        return $aField['before_label']."<div class='yucca-shop-input-label-container'>"."<label for='{$aField['input_id']}'>".$aField['before_input'].($aField['label'] ? "<span class='yucca-shop-input-label-string' style='min-width:".$this->sanitizeLength($aField['label_min_width']).";'>".$aField['label'].'</span>' : '').'<input '.$this->getAttributes($aField['attributes']).' />'.$aField['after_input'].'</label>'.'</div>'.$aField['after_label'];
    }
}
