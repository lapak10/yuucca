<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_FieldType_select extends yucca_shopAdminPageFramework_FieldType
{
    public $aFieldTypeSlugs = ['select'];
    protected $aDefaultKeys = ['label' => [], 'is_multiple' => false, 'attributes' => ['select' => ['size' => 1, 'autofocusNew' => null, 'multiple' => null, 'required' => null], 'optgroup' => [], 'option' => []]];

    protected function getStyles()
    {
        return '.yucca-shop-field-select .yucca-shop-input-label-container {vertical-align: top; }.yucca-shop-field-select .yucca-shop-input-label-container {padding-right: 1em;}';
    }

    protected function getField($aField)
    {
        $_oSelectInput = new yucca_shopAdminPageFramework_Input_select($aField['attributes']);
        if ($aField['is_multiple']) {
            $_oSelectInput->setAttribute(['select', 'multiple'], 'multiple');
        }

        return $aField['before_label']."<div class='yucca-shop-input-label-container yucca-shop-select-label' style='min-width: ".$this->sanitizeLength($aField['label_min_width']).";'>"."<label for='{$aField['input_id']}'>".$aField['before_input'].$_oSelectInput->get($aField['label']).$aField['after_input']."<div class='repeatable-field-buttons'></div>".'</label>'.'</div>'.$aField['after_label'];
    }
}
class yucca_shopAdminPageFramework_FieldType_size extends yucca_shopAdminPageFramework_FieldType_select
{
    public $aFieldTypeSlugs = ['size'];
    protected $aDefaultKeys = ['is_multiple' => false, 'units' => null, 'attributes' => ['size' => ['min' => null, 'max' => null, 'style' => 'width: 160px;'], 'unit' => ['multiple' => null, 'size' => 1, 'autofocusNew' => null, 'required' => null], 'optgroup' => [], 'option' => []]];
    protected $aDefaultUnits = ['px' => 'px', '%' => '%', 'em' => 'em', 'ex' => 'ex', 'in' => 'in', 'cm' => 'cm', 'mm' => 'mm', 'pt' => 'pt', 'pc' => 'pc'];

    protected function getStyles()
    {
        return '.yucca-shop-field-size input {text-align: right;}.yucca-shop-field-size select.size-field-select {vertical-align: 0px; }.yucca-shop-field-size label {width: auto; } .form-table td fieldset .yucca-shop-field-size label {display: inline;}';
    }

    protected function getField($aField)
    {
        $aField['units'] = $this->getElement($aField, 'units', $this->aDefaultUnits);
        $_aOutput = [];
        foreach ((array) $aField['label'] as $_isKey => $_sLabel) {
            $_aOutput[] = $this->_getFieldOutputByLabel($_isKey, $_sLabel, $aField);
        }

        return implode('', $_aOutput);
    }

    protected function _getFieldOutputByLabel($isKey, $sLabel, array $aField)
    {
        $_bMultiLabels = is_array($aField['label']);
        $_sLabel = $this->getElementByLabel($aField['label'], $isKey, $aField['label']);
        $aField['value'] = $this->getElementByLabel($aField['value'], $isKey, $aField['label']);
        $_aBaseAttributes = $_bMultiLabels ? ['name' => $aField['attributes']['name']."[{$isKey}]", 'id' => $aField['attributes']['id']."_{$isKey}", 'value' => $aField['value']] + $aField['attributes'] : $aField['attributes'];
        unset($_aBaseAttributes['unit'], $_aBaseAttributes['size']);
        $_aOutput = [$this->getElementByLabel($aField['before_label'], $isKey, $aField['label']), "<div class='yucca-shop-input-label-container yucca-shop-select-label' style='min-width: ".$this->sanitizeLength($aField['label_min_width']).";'>", $this->_getNumberInputPart($aField, $_aBaseAttributes, $isKey, $aField['label']), $this->_getUnitSelectInput($aField, $_aBaseAttributes, $isKey, $aField['label']), '</div>', $this->getElementByLabel($aField['after_label'], $isKey, $aField['label'])];

        return implode('', $_aOutput);
    }

    private function _getNumberInputPart(array $aField, array $aBaseAttributes, $isKey, $bMultiLabels)
    {
        $_aSizeAttributes = $this->_getSizeAttributes($aField, $aBaseAttributes, $bMultiLabels ? $isKey : '');
        $_aSizeLabelAttributes = ['for' => $_aSizeAttributes['id'], 'class' => $_aSizeAttributes['disabled'] ? 'disabled' : null];
        $_sLabel = $this->getElementByLabel($aField['label'], $isKey, $aField['label']);

        return '<label '.$this->getAttributes($_aSizeLabelAttributes).'>'.$this->getElement($aField, $bMultiLabels ? ['before_label', $isKey, 'size'] : ['before_label', 'size']).($aField['label'] && !$aField['repeatable'] ? "<span class='yucca-shop-input-label-string' style='min-width:".$this->sanitizeLength($aField['label_min_width']).";'>".$_sLabel.'</span>' : '').'<input '.$this->getAttributes($_aSizeAttributes).' />'.$this->getElement($aField, $bMultiLabels ? ['after_input', $isKey, 'size'] : ['after_input', 'size']).'</label>';
    }

    private function _getUnitSelectInput(array $aField, array $aBaseAttributes, $isKey, $bMultiLabels)
    {
        $_aUnitAttributes = $this->_getUnitAttributes($aField, $aBaseAttributes, $bMultiLabels ? $isKey : '');
        $_oUnitInput = new yucca_shopAdminPageFramework_Input_select($_aUnitAttributes + ['select' => $_aUnitAttributes]);
        $_aLabels = $bMultiLabels ? $this->getElement($aField, ['units', $isKey], $aField['units']) : $aField['units'];

        return '<label '.$this->getAttributes(['for' => $_aUnitAttributes['id'], 'class' => $_aUnitAttributes['disabled'] ? 'disabled' : null]).'>'.$this->getElement($aField, $bMultiLabels ? ['before_label', $isKey, 'unit'] : ['before_label', 'unit']).$_oUnitInput->get($_aLabels).$this->getElement($aField, $bMultiLabels ? ['after_input', $isKey, 'unit'] : ['after_input', 'unit'])."<div class='repeatable-field-buttons'></div>".'</label>';
    }

    private function _getUnitAttributes(array $aField, array $aBaseAttributes, $isLabelKey = '')
    {
        $_bIsMultiple = $aField['is_multiple'] ? true : $this->getElement($aField, '' === $isLabelKey ? ['attributes', 'unit', 'multiple'] : ['attributes', $isLabelKey, 'unit', 'multiple'], false);
        $_aSelectAttributes = ['type' => 'select', 'id' => $aField['input_id'].('' === $isLabelKey ? '' : '_'.$isLabelKey).'_'.'unit', 'multiple' => $_bIsMultiple ? 'multiple' : null, 'name' => $_bIsMultiple ? "{$aField['_input_name']}".('' === $isLabelKey ? '' : '['.$isLabelKey.']').'[unit][]' : "{$aField['_input_name']}".('' === $isLabelKey ? '' : '['.$isLabelKey.']').'[unit]', 'value' => $this->getElement($aField, ['value', 'unit'], '')] + $this->getElement($aField, '' === $isLabelKey ? ['attributes', 'unit'] : ['attributes', $isLabelKey, 'unit'], $this->aDefaultKeys['attributes']['unit']) + $aBaseAttributes;

        return $_aSelectAttributes;
    }

    private function _getSizeAttributes(array $aField, array $aBaseAttributes, $sLabelKey = '')
    {
        return ['type' => 'number', 'id' => $aField['input_id'].'_'.('' !== $sLabelKey ? $sLabelKey.'_' : '').'size', 'name' => $aField['_input_name'].('' !== $sLabelKey ? "[{$sLabelKey}]" : '').'[size]', 'value' => $this->getElement($aField, ['value', 'size'], '')] + $this->getElementAsArray($aField, '' === $sLabelKey ? ['attributes', 'size'] : ['attributes', $sLabelKey, 'size'], $this->aDefaultKeys['attributes']['size']) + $aBaseAttributes;
    }
}
