<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Input_select extends yucca_shopAdminPageFramework_Input_Base
{
    public $aStructureOptions = ['input_container_tag' => 'span', 'input_container_attributes' => ['class' => 'yucca-shop-input-container'], 'label_container_tag' => 'span', 'label_container_attributes' => ['class' => 'yucca-shop-input-label-string']];

    protected function construct()
    {
        if (isset($this->aField['is_multiple'])) {
            $this->aAttributes['select']['multiple'] = $this->aField['is_multiple'] ? 'multiple' : $this->getElement($this->aAttributes, ['select', 'multiple']);
        }
    }

    public function get()
    {
        $_aParams = func_get_args() + [0 => null, 1 => []];
        $_aLabels = $_aParams[0];
        $_aAttributes = $this->uniteArrays($this->getElementAsArray($_aParams, 1, []), $this->aAttributes);

        return "<{$this->aOptions['input_container_tag']} ".$this->getAttributes($this->aOptions['input_container_attributes']).'>'.'<select '.$this->getAttributes($this->_getSelectAttributes($_aAttributes)).' >'.$this->_getDropDownList($this->getAttribute('id'), $this->getAsArray(isset($_aLabels) ? $_aLabels : $this->aField['label'], true), $_aAttributes).'</select>'."</{$this->aOptions['input_container_tag']}>";
    }

    private function _getSelectAttributes(array $aBaseAttributes)
    {
        $_bIsMultiple = $this->getElement($aBaseAttributes, 'multiple') ? true : ((bool) $this->getElement($aBaseAttributes, ['select', 'multiple']));

        return $this->uniteArrays($this->getElementAsArray($aBaseAttributes, 'select', []), ['id' => $this->getAttribute('id'), 'multiple' => $_bIsMultiple ? 'multiple' : null, 'name' => $_bIsMultiple ? $this->getAttribute('name').'[]' : $this->getAttribute('name'), 'data-id' => $this->getAttribute('id')]);
    }

    private function _getDropDownList($sInputID, array $aLabels, array $aBaseAttributes)
    {
        $_aOutput = [];
        foreach ($aLabels as $__sKey => $__asLabel) {
            if (is_array($__asLabel)) {
                $_aOutput[] = $this->_getOptGroup($aBaseAttributes, $sInputID, $__sKey, $__asLabel);
                continue;
            }
            $_aOutput[] = $this->_getOptionTag($__asLabel, $this->_getOptionTagAttributes($aBaseAttributes, $sInputID, $__sKey, $this->getAsArray($aBaseAttributes['value'], true)));
        }

        return implode(PHP_EOL, $_aOutput);
    }

    private function _getOptGroup(array $aBaseAttributes, $sInputID, $sKey, $asLabel)
    {
        $_aOptGroupAttributes = isset($aBaseAttributes['optgroup'][$sKey]) && is_array($aBaseAttributes['optgroup'][$sKey]) ? $aBaseAttributes['optgroup'][$sKey] + $aBaseAttributes['optgroup'] : $aBaseAttributes['optgroup'];
        $_aOptGroupAttributes = ['label' => $sKey] + $_aOptGroupAttributes;

        return '<optgroup '.$this->getAttributes($_aOptGroupAttributes).'>'.$this->_getDropDownList($sInputID, $asLabel, $aBaseAttributes).'</optgroup>';
    }

    private function _getOptionTagAttributes(array $aBaseAttributes, $sInputID, $sKey, $aValues)
    {
        $aValues = $this->getElementAsArray($aBaseAttributes, ['option', $sKey, 'value'], $aValues);

        return ['id' => $sInputID.'_'.$sKey, 'value' => $sKey, 'selected' => in_array((string) $sKey, $aValues) ? 'selected' : null] + (isset($aBaseAttributes['option'][$sKey]) && is_array($aBaseAttributes['option'][$sKey]) ? $aBaseAttributes['option'][$sKey] + $aBaseAttributes['option'] : $aBaseAttributes['option']);
    }

    private function _getOptionTag($sLabel, array $aOptionTagAttributes = [])
    {
        return '<option '.$this->getAttributes($aOptionTagAttributes).' >'.$sLabel.'</option>';
    }
}
