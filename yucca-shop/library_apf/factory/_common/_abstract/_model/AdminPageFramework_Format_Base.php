<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class yucca_shopAdminPageFramework_Format_Base extends yucca_shopAdminPageFramework_FrameworkUtility
{
    public static $aStructure = [];
    public $aSubject = [];

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->aSubject];
        $this->aSubject = $_aParameters[0];
    }

    public function get()
    {
        return $this->aSubject;
    }
}
