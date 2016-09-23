<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class yucca_shopAdminPageFramework_Form_View___Attribute_Base extends yucca_shopAdminPageFramework_FrameworkUtility
{
    public $sContext = '';
    public $aArguments = [];
    public $aAttributes = [];

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->aArguments, $this->aAttributes];
        $this->aArguments = $_aParameters[0];
        $this->aAttributes = $_aParameters[1];
    }

    public function get()
    {
        return $this->getAttributes($this->_getFormattedAttributes());
    }

    protected function _getFormattedAttributes()
    {
        return $this->aAttributes + $this->_getAttributes();
    }

    protected function _getAttributes()
    {
        return [];
    }
}
abstract class yucca_shopAdminPageFramework_Form_View___Attribute_FieldContainer_Base extends yucca_shopAdminPageFramework_Form_View___Attribute_Base
{
    protected function _getFormattedAttributes()
    {
        $_aAttributes = $this->uniteArrays($this->getElementAsArray($this->aArguments, ['attributes', $this->sContext]), $this->aAttributes + $this->_getAttributes());
        $_aAttributes['class'] = $this->getClassAttribute($this->getElement($_aAttributes, 'class', []), $this->getElement($this->aArguments, ['class', $this->sContext], []));

        return $_aAttributes;
    }
}
class yucca_shopAdminPageFramework_Form_View___Attribute_Field extends yucca_shopAdminPageFramework_Form_View___Attribute_FieldContainer_Base
{
    public $sContext = 'field';

    protected function _getAttributes()
    {
        return ['id' => $this->aArguments['_field_container_id'], 'data-type' => $this->aArguments['type'], 'class' => 'yucca-shop-field yucca-shop-field-'.$this->aArguments['type'].$this->getAOrB($this->aArguments['attributes']['disabled'], ' disabled', '').$this->getAOrB($this->aArguments['_is_sub_field'], ' yucca-shop-subfield', '')];
    }
}
