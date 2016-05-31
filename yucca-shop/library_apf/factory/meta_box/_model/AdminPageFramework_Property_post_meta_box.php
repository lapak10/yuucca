<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Property_post_meta_box extends yucca_shopAdminPageFramework_Property_Base
{
    public $_sPropertyType = 'post_meta_box';
    public $sMetaBoxID = '';
    public $aPostTypes = [];
    public $aPages = [];
    public $sContext = 'normal';
    public $sPriority = 'default';
    public $sClassName = '';
    public $sCapability = 'edit_posts';
    public $sThickBoxTitle = '';
    public $sThickBoxButtonUseThis = '';
    public $sStructureType = 'post_meta_box';
    public $_sFormRegistrationHook = 'admin_enqueue_scripts';

    public function __construct($oCaller, $sClassName, $sCapability = 'edit_posts', $sTextDomain = 'yucca-shop', $sStructureType = 'post_meta_box')
    {
        parent::__construct($oCaller, null, $sClassName, $sCapability, $sTextDomain, $sStructureType);
    }

    protected function _getOptions()
    {
        return [];
    }
}
