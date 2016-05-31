<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_View___Description extends yucca_shopAdminPageFramework_FrameworkUtility
{
    public $aDescriptions = [];
    public $sClassAttribute = 'yucca-shop-form-element-description';

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->aDescriptions, $this->sClassAttribute];
        $this->aDescriptions = $this->getAsArray($_aParameters[0]);
        $this->sClassAttribute = $_aParameters[1];
    }

    public function get()
    {
        if (empty($this->aDescriptions)) {
            return '';
        }
        $_aOutput = [];
        foreach ($this->aDescriptions as $_sDescription) {
            $_aOutput[] = "<p class='".esc_attr($this->sClassAttribute)."'>"."<span class='description'>".$_sDescription.'</span>'.'</p>';
        }

        return implode(PHP_EOL, $_aOutput);
    }
}
