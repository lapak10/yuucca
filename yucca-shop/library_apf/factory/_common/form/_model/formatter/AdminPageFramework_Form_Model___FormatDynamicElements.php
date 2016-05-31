<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_Model___FormatDynamicElements extends yucca_shopAdminPageFramework_FrameworkUtility
{
    public $aSectionsets = [];
    public $aFieldsets = [];
    public $aSavedFormData = [];

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->aSectionsets, $this->aFieldsets, $this->aSavedFormData];
        $this->aSectionsets = $_aParameters[0];
        $this->aFieldsets = $_aParameters[1];
        $this->aSavedFormData = $_aParameters[2];
    }

    public function get()
    {
        $this->_setDynamicElements($this->aSavedFormData);

        return $this->aFieldsets;
    }

    private function _setDynamicElements($aOptions)
    {
        $aOptions = $this->castArrayContents($this->aSectionsets, $aOptions);
        foreach ($aOptions as $_sSectionID => $_aSubSectionOrFields) {
            $_aSubSection = $this->_getSubSectionFromOptions($_sSectionID, $this->getAsArray($_aSubSectionOrFields));
            if (empty($_aSubSection)) {
                continue;
            }
            $this->aFieldsets[$_sSectionID] = $_aSubSection;
        }
    }

    private function _getSubSectionFromOptions($_sSectionID, array $_aSubSectionOrFields)
    {
        $_aSubSection = [];
        $_iPrevIndex = null;
        foreach ($_aSubSectionOrFields as $_isIndexOrFieldID => $_aSubSectionOrFieldOptions) {
            if (!$this->isNumericInteger($_isIndexOrFieldID)) {
                continue;
            }
            $_iIndex = $_isIndexOrFieldID;
            $_aSubSection[$_iIndex] = $this->_getSubSectionItemsFromOptions($_aSubSection, $_sSectionID, $_iIndex, $_iPrevIndex);
            foreach ($_aSubSection[$_iIndex] as &$_aField) {
                $_aField['_section_index'] = $_iIndex;
            }
            unset($_aField);
            $_iPrevIndex = $_iIndex;
        }

        return $_aSubSection;
    }

    private function _getSubSectionItemsFromOptions(array $_aSubSection, $_sSectionID, $_iIndex, $_iPrevIndex)
    {
        if (!isset($this->aFieldsets[$_sSectionID])) {
            return [];
        }
        $_aFields = isset($this->aFieldsets[$_sSectionID][$_iIndex]) ? $this->aFieldsets[$_sSectionID][$_iIndex] : $this->getNonIntegerKeyElements($this->aFieldsets[$_sSectionID]);

        return !empty($_aFields) ? $_aFields : $this->getElementAsArray($_aSubSection, $_iPrevIndex, []);
    }
}
