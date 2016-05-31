<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_View___FieldsetTableRow extends yucca_shopAdminPageFramework_Form_View___Section_Base
{
    public $aFieldset = [];
    public $aSavedData = [];
    public $aFieldErrors = [];
    public $aFieldTypeDefinitions = [];
    public $aCallbacks = [];
    public $oMsg;

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->aFieldset, $this->aSavedData, $this->aFieldErrors, $this->aFieldTypeDefinitions, $this->aCallbacks, $this->oMsg];
        $this->aFieldset = $_aParameters[0];
        $this->aSavedData = $_aParameters[1];
        $this->aFieldErrors = $_aParameters[2];
        $this->aFieldTypeDefinitions = $_aParameters[3];
        $this->aCallbacks = $_aParameters[4];
        $this->oMsg = $_aParameters[5];
    }

    public function get()
    {
        $aFieldset = $this->aFieldset;
        if ('section_title' === $aFieldset['type']) {
            return '';
        }
        $_oFieldrowAttribute = new yucca_shopAdminPageFramework_Form_View___Attribute_Fieldrow($aFieldset, ['id' => 'fieldrow-'.$aFieldset['tag_id'], 'valign' => 'top', 'class' => 'yucca-shop-fieldrow']);

        return $this->_getFieldByContainer($aFieldset, ['open_container' => '<tr '.$_oFieldrowAttribute->get().'>', 'close_container' => '</tr>', 'open_title' => '<th>', 'close_title' => '</th>', 'open_main' => '<td '.$this->getAttributes(['colspan' => $aFieldset['show_title_column'] ? 1 : 2, 'class' => $aFieldset['show_title_column'] ? null : 'yucca-shop-field-td-no-title']).'>', 'close_main' => '</td>']);
    }

    protected function _getFieldByContainer(array $aFieldset, array $aOpenCloseTags)
    {
        $aOpenCloseTags = $aOpenCloseTags + ['open_container' => '', 'close_container' => '', 'open_title' => '', 'close_title' => '', 'open_main' => '', 'close_main' => ''];
        $_aOutput = [];
        if ($aFieldset['show_title_column']) {
            $_aOutput[] = $aOpenCloseTags['open_title'].$this->_getFieldTitle($aFieldset).$aOpenCloseTags['close_title'];
        }
        $_aOutput[] = $aOpenCloseTags['open_main'].$this->getFieldsetOutput($aFieldset).$aOpenCloseTags['close_main'];

        return $aOpenCloseTags['open_container'].implode(PHP_EOL, $_aOutput).$aOpenCloseTags['close_container'];
    }

    private function _getFieldTitle(array $aField)
    {
        $_oInputTagIDGenerator = new yucca_shopAdminPageFramework_Form_View___Generate_FieldInputID($aField, 0);

        return "<label for='".$_oInputTagIDGenerator->get()."'>"."<a id='{$aField['field_id']}'></a>"."<span title='".esc_attr(strip_tags(is_array($aField['description']) ? implode('&#10;', $aField['description']) : $aField['description']))."'>".$aField['title'].$this->_getTitleColon($aField).$this->_getToolTip($aField['tip'], $aField['field_id']).'</span>'.'</label>';
    }

    private function _getToolTip($asTip, $sElementID)
    {
        $_oToolTip = new yucca_shopAdminPageFramework_Form_View___ToolTip($asTip, $sElementID);

        return $_oToolTip->get();
    }

    private function _getTitleColon($aField)
    {
        if (!isset($aField['title']) || '' === $aField['title']) {
            return '';
        }
        if (in_array($aField['_structure_type'], ['widget', 'post_meta_box', 'page_meta_box'])) {
            return "<span class='title-colon'>:</span>";
        }
    }
}
class yucca_shopAdminPageFramework_Form_View___FieldsetRow extends yucca_shopAdminPageFramework_Form_View___FieldsetTableRow
{
    public $aFieldset = [];
    public $aSavedData = [];
    public $aFieldErrors = [];
    public $aFieldTypeDefinitions = [];
    public $aCallbacks = [];
    public $oMsg;

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->aFieldset, $this->aSavedData, $this->aFieldErrors, $this->aFieldTypeDefinitions, $this->aCallbacks, $this->oMsg];
        $this->aFieldset = $_aParameters[0];
        $this->aSavedData = $_aParameters[1];
        $this->aFieldErrors = $_aParameters[2];
        $this->aFieldTypeDefinitions = $_aParameters[3];
        $this->aCallbacks = $_aParameters[4];
        $this->oMsg = $_aParameters[5];
    }

    public function get()
    {
        $aFieldset = $this->aFieldset;
        if ('section_title' === $aFieldset['type']) {
            return '';
        }
        $_oFieldrowAttribute = new yucca_shopAdminPageFramework_Form_View___Attribute_Fieldrow($aFieldset);

        return $this->_getFieldByContainer($aFieldset, ['open_main' => '<div '.$_oFieldrowAttribute->get().'>', 'close_main' => '</div>']);
    }
}
