<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class yucca_shopAdminPageFramework_Form_Model___Modifier_Base extends yucca_shopAdminPageFramework_FrameworkUtility
{
}
class yucca_shopAdminPageFramework_Form_Model___Modifier_FilterRepeatableElements extends yucca_shopAdminPageFramework_Form_Model___Modifier_Base
{
    public $aSubject = [];
    public $aDimensionalKeys = [];

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->aSubject, $this->aDimensionalKeys];
        $this->aSubject = $_aParameters[0];
        $this->aDimensionalKeys = array_unique($_aParameters[1]);
    }

    public function get()
    {
        foreach ($this->aDimensionalKeys as $_sFlatFieldAddress) {
            $this->unsetDimensionalArrayElement($this->aSubject, explode('|', $_sFlatFieldAddress));
        }

        return $this->aSubject;
    }
}
