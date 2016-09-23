<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_View___Attribute_SectionTable extends yucca_shopAdminPageFramework_Form_View___Attribute_Base
{
    public $sContext = 'section_table';

    protected function _getAttributes()
    {
        return ['id' => 'section_table-'.$this->aArguments['_tag_id'], 'class' => $this->getClassAttribute('form-table', 'yucca-shop-section-table')];
    }
}
