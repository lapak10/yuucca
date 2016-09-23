<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_View___Attribute_SectionsTablesContainer extends yucca_shopAdminPageFramework_Form_View___Attribute_Base
{
    public $aSectionset = [];
    public $sSectionsID = '';
    public $sSectionTabSlug = '';
    public $aCollapsible = [];
    public $iSubSectionCount = 0;

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->aSectionset, $this->sSectionsID, $this->sSectionTabSlug, $this->aCollapsible, $this->iSubSectionCount];
        $this->aSectionset = $_aParameters[0];
        $this->sSectionsID = $_aParameters[1];
        $this->sSectionTabSlug = $_aParameters[2];
        $this->aCollapsible = $_aParameters[3];
        $this->iSubSectionCount = $_aParameters[4];
    }

    protected function _getAttributes()
    {
        return ['id' => $this->sSectionsID, 'class' => $this->getClassAttribute('yucca-shop-sections', $this->getAOrB(!$this->sSectionTabSlug || '_default' === $this->sSectionTabSlug, null, 'yucca-shop-section-tabs-contents'), $this->getAOrB(empty($this->aCollapsible), null, 'yucca-shop-collapsible-sections-content'.' '.'yucca-shop-collapsible-content'.' '.'accordion-section-content'), $this->getAOrB(empty($this->aSectionset['sortable']), null, 'sortable-section')), 'data-seciton_id' => $this->aSectionset['section_id'], 'data-section_address' => $this->aSectionset['section_id'], 'data-section_address_model' => $this->aSectionset['section_id'].'|'.'___i___'] + $this->_getDynamicElementArguments($this->aSectionset);
    }

    private function _getDynamicElementArguments($aSectionset)
    {
        if (empty($aSectionset['repeatable']) && empty($aSectionset['sortable'])) {
            return [];
        }
        $aSectionset['_index'] = null;
        $_oSectionNameGenerator = new yucca_shopAdminPageFramework_Form_View___Generate_SectionName($aSectionset, $aSectionset['_caller_object']->aCallbacks['hfSectionName']);

        return ['data-largest_index' => max((int) $this->iSubSectionCount - 1, 0), 'data-section_id_model' => $aSectionset['section_id'].'__'.'___i___', 'data-flat_section_name_model' => $aSectionset['section_id'].'|___i___', 'data-section_name_model' => $_oSectionNameGenerator->getModel()];
    }
}
