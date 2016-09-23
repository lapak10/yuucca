<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Format_PageResource_Script extends yucca_shopAdminPageFramework_Format_Base
{
    public static $aStructure = ['src' => null, 'handle_id' => null, 'dependencies' => [], 'version' => false, 'translation' => [], 'in_footer' => false];
    public $asSubject = '';

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->asSubject];
        $this->asSubject = $_aParameters[0];
    }

    public function get()
    {
        return $this->_getFormatted($this->asSubject);
    }

    private function _getFormatted($asSubject)
    {
        $_aSubject = [];
        if (is_string($asSubject)) {
            $_aSubject['src'] = $asSubject;
        }

        return $_aSubject + self::$aStructure;
    }
}
