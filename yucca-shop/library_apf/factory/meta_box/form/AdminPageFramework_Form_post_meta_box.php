<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_post_meta_box extends yucca_shopAdminPageFramework_Form_Meta
{
    public $sStructureType = 'post_meta_box';

    public function construct()
    {
        $this->_addDefaultResources();
    }

    private function _addDefaultResources()
    {
        $_oCSS = new yucca_shopAdminPageFramework_Form_View___CSS_meta_box();
        $this->addResource('inline_styles', $_oCSS->get());
    }
}
