<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class yucca_shopAdminPageFramework_Input_Base extends yucca_shopAdminPageFramework_FrameworkUtility
{
    public $aField = [];
    public $aAttributes = [];
    public $aOptions = [];
    public $aStructureOptions = ['input_container_tag' => 'span', 'input_container_attributes' => ['class' => 'yucca-shop-input-container'], 'label_container_tag' => 'span', 'label_container_attributes' => ['class' => 'yucca-shop-input-label-string']];

    public function __construct(array $aAttributes, array $aOptions = [])
    {
        $this->aAttributes = $this->getElementAsArray($aAttributes, 'attributes', $aAttributes);
        $this->aOptions = $aOptions + $this->aStructureOptions;
        $this->aField = $aAttributes;
        $this->construct();
    }

    protected function construct()
    {
    }

    public function get()
    {
    }

    public function getAttribute()
    {
        $_aParams = func_get_args() + [0 => null, 1 => null];

        return isset($_aParams[0]) ? $this->getElement($this->aAttributes, $_aParams[0], $_aParams[1]) : $this->aAttributes();
    }

    public function addClass()
    {
        foreach (func_get_args() as $_asSelectors) {
            $this->aAttributes['class'] = $this->getClassAttribute($this->aAttributes['class'], $_asSelectors);
        }

        return $this->aAttributes['class'];
    }

    public function setAttribute()
    {
        $_aParams = func_get_args() + [0 => null, 1 => null];
        $this->setMultiDimensionalArray($this->aAttributes, $this->getElementAsArray($_aParams, 0), $_aParams[1]);
    }

    public function setAttributesByKey($sKey)
    {
        $this->aAttributes = $this->getAttributesByKey($sKey);
    }

    public function getAttributesByKey()
    {
        return [];
    }

    public function getAttributeArray()
    {
        $_aParams = func_get_args();

        return call_user_func_array([$this, 'getAttributesByKey'], $_aParams);
    }
}
class yucca_shopAdminPageFramework_Input_checkbox extends yucca_shopAdminPageFramework_Input_Base
{
    public function get()
    {
        $_aParams = func_get_args() + [0 => '', 1 => []];
        $_sLabel = $_aParams[0];
        $_aAttributes = $this->uniteArrays($this->getElementAsArray($_aParams, 1, []), $this->aAttributes);

        return "<{$this->aOptions['input_container_tag']} ".$this->getAttributes($this->aOptions['input_container_attributes']).'>'.'<input '.$this->getAttributes(['type' => 'hidden', 'class' => $_aAttributes['class'], 'name' => $_aAttributes['name'], 'value' => '0']).' />'.'<input '.$this->getAttributes($_aAttributes).' />'."</{$this->aOptions['input_container_tag']}>"."<{$this->aOptions['label_container_tag']} ".$this->getAttributes($this->aOptions['label_container_attributes']).'>'.$_sLabel."</{$this->aOptions['label_container_tag']}>";
    }

    public function getAttributesByKey()
    {
        $_aParams = func_get_args() + [0 => ''];
        $_sKey = $_aParams[0];
        $_bIsMultiple = '' !== $_sKey;

        return $this->getElement($this->aAttributes, $_sKey, []) + ['type' => 'checkbox', 'id' => $this->aAttributes['id'].'_'.$_sKey, 'checked' => $this->_getCheckedAttributeValue($_sKey), 'value' => 1, 'name' => $_bIsMultiple ? "{$this->aAttributes['name']}[{$_sKey}]" : $this->aAttributes['name'], 'data-id' => $this->aAttributes['id']] + $this->aAttributes;
    }

    private function _getCheckedAttributeValue($_sKey)
    {
        $_aValueDimension = '' === $_sKey ? ['value'] : ['value', $_sKey];

        return $this->getElement($this->aAttributes, $_aValueDimension) ? 'checked' : null;
    }
}
