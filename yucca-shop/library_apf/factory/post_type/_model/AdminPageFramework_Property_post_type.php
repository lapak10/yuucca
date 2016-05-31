<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Property_post_type extends yucca_shopAdminPageFramework_Property_Base
{
    public $_sPropertyType = 'post_type';
    public $sPostType = '';
    public $aPostTypeArgs = [];
    public $sClassName = '';
    public $aColumnHeaders = ['cb' => '<input type="checkbox" />', 'title' => 'Title', 'author' => 'Author', 'comments' => '<div class="comment-grey-bubble"></div>', 'date' => 'Date'];
    public $aColumnSortable = ['title' => true, 'date' => true];
    public $sCallerPath = '';
    public $aTaxonomies;
    public $aTaxonomyObjectTypes = [];
    public $aTaxonomyTableFilters = [];
    public $aTaxonomyRemoveSubmenuPages = [];
    public $bEnableAutoSave = true;
    public $bEnableAuthorTableFileter = false;
    public $aTaxonomySubMenuOrder = [];
}
