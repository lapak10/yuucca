<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_Model___BuiltInFieldTypeDefinitions
{
    protected static $_aDefaultFieldTypeSlugs = ['default', 'text', 'number', 'textarea', 'radio', 'checkbox', 'select', 'hidden', 'file', 'submit', 'import', 'export', 'image', 'media', 'color', 'taxonomy', 'posttype', 'size', 'section_title', 'system'];
    public $sCallerID = '';
    public $oMsg;

    public function __construct($sCallerID, $oMsg)
    {
        $this->sCallerID = $sCallerID;
        $this->oMsg = $oMsg;
    }

    public function get()
    {
        $_aFieldTypeDefinitions = [];
        foreach (self::$_aDefaultFieldTypeSlugs as $_sFieldTypeSlug) {
            $_sFieldTypeClassName = "yucca_shopAdminPageFramework_FieldType_{$_sFieldTypeSlug}";
            $_oFieldType = new $_sFieldTypeClassName($this->sCallerID, null, $this->oMsg, false);
            foreach ($_oFieldType->aFieldTypeSlugs as $_sSlug) {
                $_aFieldTypeDefinitions[$_sSlug] = $_oFieldType->getDefinitionArray();
            }
        }

        return $_aFieldTypeDefinitions;
    }
}
