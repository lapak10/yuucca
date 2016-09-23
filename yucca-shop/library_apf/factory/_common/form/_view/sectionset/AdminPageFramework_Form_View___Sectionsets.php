<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_View___Sectionsets extends yucca_shopAdminPageFramework_Form_View___Section_Base
{
    public $aArguments = ['structure_type' => 'admin_page', 'capability' => '', 'nested_depth' => 0];
    public $aStructure = ['field_type_definitions' => [], 'sectionsets' => [], 'fieldsets' => []];
    public $aSavedData = [];
    public $aFieldErrors = [];
    public $aCallbacks = ['section_head_output' => null, 'fieldset_output' => null];
    public $oMsg;

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->aArguments, $this->aStructure, $this->aSavedData, $this->aFieldErrors, $this->aCallbacks, $this->oMsg];
        $this->aArguments = $this->getAsArray($_aParameters[0]) + $this->aArguments;
        $this->aStructure = $this->getAsArray($_aParameters[1]) + $this->aStructure;
        $this->aSavedData = $this->getAsArray($_aParameters[2]);
        $this->aFieldErrors = $this->getAsArray($_aParameters[3]);
        $this->aCallbacks = $this->getAsArray($_aParameters[4]) + $this->aCallbacks;
        $this->oMsg = $_aParameters[5];
    }

    public function get()
    {
        $_oFormatSectionsetsByTab = new yucca_shopAdminPageFramework_Form_View___Format_SectionsetsByTab($this->aStructure['sectionsets'], $this->aStructure['fieldsets'], $this->aArguments['nested_depth']);
        $_aOutput = [];
        foreach ($_oFormatSectionsetsByTab->getTabs() as $_sSectionTabSlug) {
            $_aOutput[] = $this->_getFormOutput($_oFormatSectionsetsByTab->getSectionsets($_sSectionTabSlug), $_oFormatSectionsetsByTab->getFieldsets($_sSectionTabSlug), $_sSectionTabSlug, $this->aCallbacks);
        }
        $_oDebugInfo = new yucca_shopAdminPageFramework_Form_View___DebugInfo($this->aArguments['structure_type'], $this->oMsg);
        $_sOutput = implode(PHP_EOL, $_aOutput);
        $_sElementID = 'yucca-shop-sectionsets-'.uniqid();

        return $this->_getSpinnerOutput($_sOutput)."<div id='{$_sElementID}' class='yucca-shop-sctionsets yucca-shop-form-js-on'>".$_sOutput.yucca_shopAdminPageFramework_Form_View___Script_SectionTab::getEnabler().$_oDebugInfo->get().'</div>';
    }

    private function _getSpinnerOutput($_sOutput)
    {
        if (trim($_sOutput)) {
            return "<div class='yucca-shop-form-loading' style='display: none;'>".$this->oMsg->get('loading').'</div>';
        }

        return '';
    }

    private function _getFormOutput(array $aSectionsets, array $aFieldsets, $sSectionTabSlug, $aCallbacks)
    {
        $_sSectionSet = $this->_getSectionsetsTables($aSectionsets, $aFieldsets, $aCallbacks);

        return $_sSectionSet ? '<div '.$this->getAttributes(['class' => 'yucca-shop-sectionset', 'id' => "sectionset-{$sSectionTabSlug}_".md5(serialize($aSectionsets))]).'>'.$_sSectionSet.'</div>' : '';
    }

    private function _getSectionsetsTables(array $aSectionsets, array $aFieldsets, array $aCallbacks)
    {
        if (empty($aSectionsets)) {
            return '';
        }
        if (!count($aFieldsets)) {
            return '';
        }
        $_aFirstSectionset = $this->getFirstElement($aSectionsets);
        $_sSectionTabSlug = '';
        $_aOutputs = ['section_tab_list' => [], 'section_contents' => [], 'count_subsections' => 0];
        $_sThisSectionID = $_aFirstSectionset['section_id'];
        $_sSectionsID = 'sections-'.$_sThisSectionID;
        $_aCollapsible = $this->_getCollapsibleArgumentForSections($_aFirstSectionset);
        foreach ($aSectionsets as $_aSectionset) {
            $_sSectionTabSlug = $_aSectionset['section_tab_slug'];
            $_aOutputs = $this->_getSectionsetTable($_aOutputs, $_sSectionsID, $_aSectionset, $aFieldsets);
        }
        $_aOutputs['section_contents'] = array_filter($_aOutputs['section_contents']);

        return $this->_getFormattedSectionsTablesOutput($_aOutputs, $_aFirstSectionset, $_sSectionsID, $this->getAsArray($_aCollapsible), $_sSectionTabSlug);
    }

    private function _getCollapsibleArgumentForSections(array $aSectionset = [])
    {
        $_oArgumentFormater = new yucca_shopAdminPageFramework_Form_Model___Format_CollapsibleSection($aSectionset['collapsible'], $aSectionset['title'], $aSectionset);
        $_aCollapsible = $this->getAsArray($_oArgumentFormater->get());

        return isset($_aCollapsible['container']) && 'sections' === $_aCollapsible['container'] ? $_aCollapsible : [];
    }

    private function _getSectionsetTable($_aOutputs, $_sSectionsID, array $_aSection, array $aFieldsInSections)
    {
        if (!$this->isSectionsetVisible($_aSection)) {
            return $_aOutputs;
        }
        $_aOutputs['section_contents'][] = $this->_getUnsetFlagSectionInputTag($_aSection);
        $_aSubSections = $this->getIntegerKeyElements($this->getElementAsArray($aFieldsInSections, $_aSection['_section_path'], []));
        $_aOutputs['count_subsections'] = count($_aSubSections);
        if ($_aOutputs['count_subsections']) {
            return $this->_getSubSections($_aOutputs, $_sSectionsID, $_aSection, $_aSubSections);
        }
        $_oEachSectionArguments = new yucca_shopAdminPageFramework_Form_Model___Format_EachSection($_aSection, null, [], $_sSectionsID);
        $_aOutputs = $this->_getSectionTableWithTabList($_aOutputs, $_oEachSectionArguments->get(), $this->getElementAsArray($aFieldsInSections, $_aSection['_section_path'], []));

        return $_aOutputs;
    }

    private function _getSubSections($_aOutputs, $_sSectionsID, $_aSection, $_aSubSections)
    {
        if (!empty($_aSection['repeatable'])) {
            $_aOutputs['section_contents'][] = yucca_shopAdminPageFramework_Form_View___Script_RepeatableSection::getEnabler($_sSectionsID, $_aOutputs['count_subsections'], $_aSection['repeatable'], $this->oMsg);
            $_aOutputs['section_contents'][] = $this->_getRepeatableSectionFlagTag($_aSection);
        }
        if (!empty($_aSection['sortable'])) {
            $_aOutputs['section_contents'][] = yucca_shopAdminPageFramework_Form_View___Script_SortableSection::getEnabler($_sSectionsID, $_aSection['sortable'], $this->oMsg);
            $_aOutputs['section_contents'][] = $this->_getSortableSectionFlagTag($_aSection);
        }
        $_aSubSections = $this->numerizeElements($_aSubSections);
        foreach ($_aSubSections as $_iIndex => $_aFields) {
            $_oEachSectionArguments = new yucca_shopAdminPageFramework_Form_Model___Format_EachSection($_aSection, $_iIndex, $_aSubSections, $_sSectionsID);
            $_aOutputs = $this->_getSectionTableWithTabList($_aOutputs, $_oEachSectionArguments->get(), $_aFields);
        }

        return $_aOutputs;
    }

    private function _getRepeatableSectionFlagTag(array $aSection)
    {
        return $this->getHTMLTag('input', ['class' => 'element-address', 'type' => 'hidden', 'name' => '__repeatable_elements_'.$aSection['_structure_type'].'['.$aSection['section_id'].']', 'value' => $aSection['section_id']]);
    }

    private function _getSortableSectionFlagTag(array $aSection)
    {
        return $this->getHTMLTag('input', ['class' => 'element-address', 'type' => 'hidden', 'name' => '__sortable_elements_'.$aSection['_structure_type'].'['.$aSection['section_id'].']', 'value' => $aSection['section_id']]);
    }

    private function _getUnsetFlagSectionInputTag(array $aSection)
    {
        if (false !== $aSection['save']) {
            return '';
        }

        return $this->getHTMLTag('input', ['type' => 'hidden', 'name' => '__unset_'.$aSection['_structure_type'].'['.$aSection['section_id'].']', 'value' => '__dummy_option_key|'.$aSection['section_id'], 'class' => 'unset-element-names element-address']);
    }

    private function _getSectionTableWithTabList(array $_aOutputs, array $aSectionset, $aFieldsetsPerSection)
    {
        $_aOutputs['section_tab_list'][] = $this->_getTabList($aSectionset, $aFieldsetsPerSection, $this->aCallbacks['fieldset_output']);
        $_oSectionTable = new yucca_shopAdminPageFramework_Form_View___Section($this->aArguments, $aSectionset, $this->aStructure, $aFieldsetsPerSection, $this->aSavedData, $this->aFieldErrors, $this->aStructure['field_type_definitions'], $this->aCallbacks, $this->oMsg);
        $_aOutputs['section_contents'][] = $_oSectionTable->get();

        return $_aOutputs;
    }

    private function _getFormattedSectionsTablesOutput(array $aOutputs, $aSectionset, $sSectionsID, array $aCollapsible, $sSectionTabSlug)
    {
        if (empty($aOutputs['section_contents'])) {
            return '';
        }
        $_oCollapsibleSectionTitle = new yucca_shopAdminPageFramework_Form_View___CollapsibleSectionTitle(['title' => $this->getElement($aCollapsible, 'title', ''), 'tag' => 'h3', 'section_index' => null, 'collapsible' => $aCollapsible, 'container_type' => 'sections', 'sectionset' => $aSectionset], [], $this->aSavedData, $this->aFieldErrors, $this->aStructure['field_type_definitions'], $this->oMsg, $this->aCallbacks);
        $_oSectionsTablesContainerAttributes = new yucca_shopAdminPageFramework_Form_View___Attribute_SectionsTablesContainer($aSectionset, $sSectionsID, $sSectionTabSlug, $aCollapsible, $aOutputs['count_subsections']);

        return $_oCollapsibleSectionTitle->get().'<div '.$_oSectionsTablesContainerAttributes->get().'>'.$this->_getSectionTabList($sSectionTabSlug, $aOutputs['section_tab_list']).implode(PHP_EOL, $aOutputs['section_contents']).'</div>';
    }

    private function _getSectionTabList($sSectionTabSlug, array $aSectionTabList)
    {
        return $sSectionTabSlug ? "<ul class='yucca-shop-section-tabs nav-tab-wrapper'>".implode(PHP_EOL, $aSectionTabList).'</ul>' : '';
    }

    private function _getTabList(array $aSection, array $aFields, $hfFieldCallback)
    {
        if (!$aSection['section_tab_slug']) {
            return '';
        }
        $iSectionIndex = $aSection['_index'];
        $_sSectionTagID = 'section-'.$aSection['section_id'].'__'.$iSectionIndex;
        $_aTabAttributes = $aSection['attributes']['tab'] + ['class' => 'yucca-shop-section-tab nav-tab', 'id' => "section_tab-{$_sSectionTagID}", 'style' => null];
        $_aTabAttributes['class'] = $this->getClassAttribute($_aTabAttributes['class'], $aSection['class']['tab']);
        $_aTabAttributes['style'] = $this->getStyleAttribute($_aTabAttributes['style'], $aSection['hidden'] ? 'display:none' : null);
        $_oSectionTitle = new yucca_shopAdminPageFramework_Form_View___SectionTitle(['title' => $aSection['title'], 'tag' => 'h4', 'section_index' => $iSectionIndex, 'sectionset' => $aSection], $aFields, $this->aSavedData, $this->aFieldErrors, $this->aStructure['field_type_definitions'], $this->oMsg, $this->aCallbacks);

        return '<li '.$this->getAttributes($_aTabAttributes).'>'."<a href='#{$_sSectionTagID}'>".$_oSectionTitle->get().'</a>'.'</li>';
    }
}
