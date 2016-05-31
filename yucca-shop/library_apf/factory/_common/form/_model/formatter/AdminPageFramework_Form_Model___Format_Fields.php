<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_Model___Format_Fields extends yucca_shopAdminPageFramework_Form_Model___Format_FormField_Base
{
    public static $aStructure = [];
    public $aField = [];
    public $aOptions = [];

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->aField, $this->aOptions];
        $this->aField = $_aParameters[0];
        $this->aOptions = $_aParameters[1];
    }

    public function get()
    {
        $_mSavedValue = $this->_getStoredInputFieldValue($this->aField, $this->aOptions);
        $_aFields = $this->_getFieldsWithSubs($this->aField, $_mSavedValue);
        $this->_setSavedFieldsValue($_aFields, $_mSavedValue, $this->aField);
        $this->_setFieldsValue($_aFields);

        return $_aFields;
    }

    private function _getFieldsWithSubs(array $aField, $mSavedValue)
    {
        $aFirstField = [];
        $aSubFields = [];
        $this->_divideMainAndSubFields($aField, $aFirstField, $aSubFields);
        $this->_fillRepeatableElements($aField, $aSubFields, $mSavedValue);
        $this->_fillSubFields($aSubFields, $aFirstField);

        return array_merge([$aFirstField], $aSubFields);
    }

    private function _divideMainAndSubFields(array $aField, array &$aFirstField, array &$aSubFields)
    {
        foreach ($aField as $_nsIndex => $_mFieldElement) {
            if (is_numeric($_nsIndex)) {
                $aSubFields[] = $_mFieldElement;
            } else {
                $aFirstField[$_nsIndex] = $_mFieldElement;
            }
        }
    }

    private function _fillRepeatableElements(array $aField, array &$aSubFields, $mSavedValue)
    {
        if (!$aField['repeatable']) {
            return;
        }
        $_aSavedValues = (array) $mSavedValue;
        unset($_aSavedValues[0]);
        foreach ($_aSavedValues as $_iIndex => $vValue) {
            $aSubFields[$_iIndex - 1] = isset($aSubFields[$_iIndex - 1]) && is_array($aSubFields[$_iIndex - 1]) ? $aSubFields[$_iIndex - 1] : [];
        }
    }

    private function _fillSubFields(array &$aSubFields, array $aFirstField)
    {
        foreach ($aSubFields as &$_aSubField) {
            $_aLabel = $this->getElement($_aSubField, 'label', $this->getElement($aFirstField, 'label', null));
            $_aSubField = $this->uniteArrays($_aSubField, $aFirstField);
            $_aSubField['label'] = $_aLabel;
        }
    }

    private function _setSavedFieldsValue(array &$aFields, $mSavedValue, $aField)
    {
        $_bHasSubFields = count($aFields) > 1 || $aField['repeatable'] || $aField['sortable'];
        if (!$_bHasSubFields) {
            $aFields[0]['_saved_value'] = $mSavedValue;
            $aFields[0]['_is_multiple_fields'] = false;

            return;
        }
        foreach ($aFields as $_iIndex => &$_aThisField) {
            $_aThisField['_saved_value'] = $this->getElement($mSavedValue, $_iIndex, null);
            $_aThisField['_is_multiple_fields'] = true;
        }
    }

    private function _setFieldsValue(array &$aFields)
    {
        foreach ($aFields as &$_aField) {
            $_aField['_is_value_set_by_user'] = isset($_aField['value']);
            $_aField['value'] = $this->_getSetFieldValue($_aField);
        }
    }

    private function _getSetFieldValue(array $aField)
    {
        if (isset($aField['value'])) {
            return $aField['value'];
        }
        if (isset($aField['_saved_value'])) {
            return $aField['_saved_value'];
        }
        if (isset($aField['default'])) {
            return $aField['default'];
        }
    }

    private function _getStoredInputFieldValue($aField, $aOptions)
    {
        if (!isset($aField['section_id']) || '_default' === $aField['section_id']) {
            return $this->getElement($aOptions, $aField['field_id'], null);
        }
        $_aSectionPath = $aField['_section_path_array'];
        $_aFieldPath = $aField['_field_path_array'];
        if (isset($aField['_section_index'])) {
            return $this->getElement($aOptions, array_merge($_aSectionPath, [$aField['_section_index']], $_aFieldPath), null);
        }

        return $this->getElement($aOptions, array_merge($_aSectionPath, $_aFieldPath), null);
    }
}
