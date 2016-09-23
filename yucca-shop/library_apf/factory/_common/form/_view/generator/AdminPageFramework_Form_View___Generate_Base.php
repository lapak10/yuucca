<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class yucca_shopAdminPageFramework_Form_View___Generate_Base extends yucca_shopAdminPageFramework_FrameworkUtility
{
    public $aArguments = [];

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->aArguments];
        $this->aArguments = $_aParameters[0];
    }

    public function get()
    {
        return '';
    }
}
