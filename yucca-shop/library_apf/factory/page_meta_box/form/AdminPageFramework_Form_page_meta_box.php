<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_page_meta_box extends yucca_shopAdminPageFramework_Form_post_meta_box
{
    public $sStructureType = 'page_meta_box';

    public function construct()
    {
        add_filter('options_'.$this->aArguments['caller_id'], [$this, '_replyToSanitizeSavedFormData'], 5);
        parent::construct();
    }

    public function _replyToSanitizeSavedFormData($aSavedFormData)
    {
        return $this->castArrayContents($this->getDataStructureFromAddedFieldsets(), $aSavedFormData);
    }
}
