<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class yucca_shopAdminPageFramework_MetaBox_Page extends yucca_shopAdminPageFramework_PageMetaBox
{
    public function __construct($sMetaBoxID, $sTitle, $asPageSlugs = [], $sContext = 'normal', $sPriority = 'default', $sCapability = 'manage_options', $sTextDomain = 'yucca-shop')
    {
        trigger_error(sprintf(__('The class <code>%1$s</code> is deprecated. Use <code>%2$s</code> instead.', 'yucca-shop'), __CLASS__, 'yucca_shopAdminPageFramework_PageMetaBox'), E_USER_NOTICE);
        parent::__construct($sMetaBoxID, $sTitle, $asPageSlugs, $sContext, $sPriority, $sCapability, $sTextDomain);
    }
}
