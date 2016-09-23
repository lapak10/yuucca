<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Widget_Factory extends WP_Widget
{
    public $oCaller;

    public function __construct($oCaller, $sWidgetTitle, array $aArguments = [])
    {
        $aArguments = $aArguments + ['classname' => 'admin_page_framework_widget', 'description' => ''];
        parent::__construct($oCaller->oProp->sClassName, $sWidgetTitle, $aArguments);
        $this->oCaller = $oCaller;
    }

    public function widget($aArguments, $aFormData)
    {
        echo $aArguments['before_widget'];
        $this->oCaller->oUtil->addAndDoActions($this->oCaller, 'do_'.$this->oCaller->oProp->sClassName, $this->oCaller);
        $_sContent = $this->oCaller->oUtil->addAndApplyFilters($this->oCaller, "content_{$this->oCaller->oProp->sClassName}", $this->oCaller->content('', $aArguments, $aFormData), $aArguments, $aFormData);
        echo $this->_getTitle($aArguments, $aFormData);
        echo $_sContent;
        echo $aArguments['after_widget'];
    }

    private function _getTitle(array $aArguments, array $aFormData)
    {
        if (!$this->oCaller->oProp->bShowWidgetTitle) {
            return '';
        }
        $_sTitle = apply_filters('widget_title', $this->oCaller->oUtil->getElement($aFormData, 'title', ''), $aFormData, $this->id_base);
        if (!$_sTitle) {
            return '';
        }

        return $aArguments['before_title'].$_sTitle.$aArguments['after_title'];
    }

    public function update($aSubmittedFormData, $aSavedFormData)
    {
        return $this->oCaller->oUtil->addAndApplyFilters($this->oCaller, "validation_{$this->oCaller->oProp->sClassName}", call_user_func_array([$this->oCaller, 'validate'], [$aSubmittedFormData, $aSavedFormData, $this->oCaller]), $aSavedFormData, $this->oCaller);
    }

    public function form($aSavedFormData)
    {
        $this->oCaller->oForm->aCallbacks = $this->_getFormCallbacks();
        $this->oCaller->oProp->aOptions = $aSavedFormData;
        $this->_loadFrameworkFactory();
        $this->oCaller->_printWidgetForm();
        $_aFieldTypeDefinitions = $this->oCaller->oForm->aFieldTypeDefinitions;
        $_aSectionsets = $this->oCaller->oForm->aSectionsets;
        $_aFieldsets = $this->oCaller->oForm->aFieldsets;
        $this->oCaller->oForm = new yucca_shopAdminPageFramework_Form_widget(['register_if_action_already_done' => false] + $this->oCaller->oProp->aFormArguments, $this->oCaller->oForm->aCallbacks, $this->oCaller->oMsg);
        $this->oCaller->oForm->aFieldTypeDefinitions = $_aFieldTypeDefinitions;
        $this->oCaller->oForm->aSectionsets = $_aSectionsets;
        $this->oCaller->oForm->aFieldsets = $_aFieldsets;
    }

    private function _loadFrameworkFactory()
    {
        if ($this->oCaller->oUtil->hasBeenCalled('_widget_load_'.$this->oCaller->oProp->sClassName)) {
            $this->oCaller->oForm->aSavedData = $this->_replyToGetSavedFormData();

            return;
        }
        $this->oCaller->load($this->oCaller);
        $this->oCaller->oUtil->addAndDoActions($this->oCaller, ['load_'.$this->oCaller->oProp->sClassName], $this->oCaller);
    }

    private function _getFormCallbacks()
    {
        return ['hfID' => [$this, 'get_field_id'], 'hfTagID' => [$this, 'get_field_id'], 'hfName' => [$this, '_replyToGetFieldName'], 'hfInputName' => [$this, '_replyToGetFieldInputName'], 'saved_data' => [$this, '_replyToGetSavedFormData']] + $this->oCaller->oProp->getFormCallbacks();
    }

    public function _replyToGetSavedFormData()
    {
        return $this->oCaller->oUtil->addAndApplyFilter($this->oCaller, 'options_'.$this->oCaller->oProp->sClassName, $this->oCaller->oProp->aOptions, $this->id);
    }

    public function _replyToGetFieldName()
    {
        $_aParams = func_get_args() + [null, null, null];
        $aFieldset = $_aParams[1];

        return $this->_getNameAttributeDimensions($aFieldset);
    }

    private function _getNameAttributeDimensions($aFieldset)
    {
        $_sSectionIndex = isset($aFieldset['section_id'], $aFieldset['_section_index']) ? "[{$aFieldset['_section_index']}]" : '';
        $_sDimensions = $this->oCaller->isSectionSet($aFieldset) ? $aFieldset['section_id'].']'.$_sSectionIndex.'['.$aFieldset['field_id'] : $aFieldset['field_id'];

        return 'widget-'.$this->id_base.'['.$this->number.']['.$_sDimensions.']';
    }

    public function _replyToGetFieldInputName()
    {
        $_aParams = func_get_args() + [null, null, null];
        $aFieldset = $_aParams[1];
        $sIndex = $_aParams[2];
        $_sIndex = $this->oCaller->oUtil->getAOrB('0' !== $sIndex && empty($sIndex), '', '['.$sIndex.']');

        return $this->_replyToGetFieldName('', $aFieldset).$_sIndex;
    }
}
