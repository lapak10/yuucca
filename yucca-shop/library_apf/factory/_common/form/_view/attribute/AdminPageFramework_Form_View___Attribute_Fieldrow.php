<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_View___Attribute_Fieldrow extends yucca_shopAdminPageFramework_Form_View___Attribute_FieldContainer_Base
{
    public $sContext = 'fieldrow';

    protected function _getFormattedAttributes()
    {
        $_aAttributes = parent::_getFormattedAttributes();
        if ($this->aArguments['hidden']) {
            $_aAttributes['style'] = $this->getStyleAttribute($this->getElement($_aAttributes, 'style', []), 'display:none');
        }

        return $_aAttributes;
    }
}
