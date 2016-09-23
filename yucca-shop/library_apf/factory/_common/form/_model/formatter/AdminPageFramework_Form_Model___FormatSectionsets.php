<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_Model___FormatSectionsets extends yucca_shopAdminPageFramework_Form_Base
{
    public $sStructureType = '';
    public $aSectionsets = [];
    public $sCapability = '';
    public $aCallbacks = ['sectionset_before_output' => null];
    public $oCallerForm;

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->aSectionsets, $this->sStructureType, $this->sCapability, $this->aCallbacks, $this->oCallerForm];
        $this->aSectionsets = $_aParameters[0];
        $this->sStructureType = $_aParameters[1];
        $this->sCapability = $_aParameters[2];
        $this->aCallbacks = $_aParameters[3];
        $this->oCallerForm = $_aParameters[4];
    }

    public function get()
    {
        if (empty($this->aSectionsets)) {
            return [];
        }
        $_aSectionsets = $this->_getSectionsetsFormatted([], $this->aSectionsets, [], $this->sCapability);

        return $_aSectionsets;
    }

    private function _getSectionsetsFormatted($_aNewSectionsets, $aSectionsetsToParse, $aSectionPath, $sCapability)
    {
        foreach ($aSectionsetsToParse as $_sSectionPath => $_aSectionset) {
            if (!is_array($_aSectionset)) {
                continue;
            }
            $_aSectionPath = array_merge($aSectionPath, [$_aSectionset['section_id']]);
            $_sSectionPath = implode('|', $_aSectionPath);
            $_aSectionsetFormatter = new yucca_shopAdminPageFramework_Form_Model___FormatSectionset($_aSectionset, $_sSectionPath, $this->sStructureType, $sCapability, count($_aNewSectionsets), $this->oCallerForm);
            $_aSectionset = $this->callBack($this->aCallbacks['sectionset_before_output'], [$_aSectionsetFormatter->get()]);
            if (empty($_aSectionset)) {
                continue;
            }
            $_aNewSectionsets[$_sSectionPath] = $_aSectionset;
            $_aNewSectionsets = $this->_getNestedSections($_aNewSectionsets, $_aSectionset, $_aSectionPath, $_aSectionset['capability']);
        }
        uasort($_aNewSectionsets, [$this, 'sortArrayByKey']);

        return $_aNewSectionsets;
    }

    private function _getNestedSections($aSectionsetsToEdit, $aSectionset, $aSectionPath, $sCapability)
    {
        if (!$this->_hasNestedSections($aSectionset)) {
            return $aSectionsetsToEdit;
        }

        return $this->_getSectionsetsFormatted($aSectionsetsToEdit, $aSectionset['content'], $aSectionPath, $sCapability);
    }

    private function _hasNestedSections($aSectionset)
    {
        $aSectionset = $aSectionset + ['content' => null];
        if (!is_array($aSectionset['content'])) {
            return false;
        }
        $_aContents = $aSectionset['content'];
        $_aFirstItem = $this->getFirstElement($_aContents);

        return is_scalar($this->getElement($_aFirstItem, 'section_id', null));
    }
}
