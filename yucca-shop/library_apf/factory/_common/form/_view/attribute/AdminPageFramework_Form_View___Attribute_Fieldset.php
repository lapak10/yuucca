<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_View___Attribute_Fieldset extends yucca_shopAdminPageFramework_Form_View___Attribute_FieldContainer_Base
{
    public $sContext = 'fieldset';

    protected function _getAttributes()
    {
        return ['id' => $this->sContext.'-'.$this->aArguments['tag_id'], 'class' => 'yucca-shop-'.$this->sContext, 'data-field_id' => $this->aArguments['tag_id']];
    }
}
