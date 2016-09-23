<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_Model___SetFieldResources extends yucca_shopAdminPageFramework_Form_Base
{
    public $aArguments = [];
    public $aFieldsets = [];
    public $aResources = ['inline_styles' => [], 'inline_styles_ie' => [], 'inline_scripts' => [], 'src_styles' => [], 'src_scripts' => []];
    public $aFieldTypeDefinitions = [];
    public $aCallbacks = ['is_fieldset_registration_allowed' => null];

    public function __construct()
    {
        $_aParameters = func_get_args() + [$this->aArguments, $this->aFieldsets, $this->aResources, $this->aFieldTypeDefinitions, $this->aCallbacks];
        $this->aArguments = $_aParameters[0];
        $this->aFieldsets = $_aParameters[1];
        $this->aResources = $_aParameters[2];
        $this->aFieldTypeDefinitions = $_aParameters[3];
        $this->aCallbacks = $_aParameters[4] + $this->aCallbacks;
    }

    public function get()
    {
        $this->_setCommon();
        $this->_set();

        return $this->aResources;
    }

    private static $_bCalled = false;

    private function _setCommon()
    {
        if (self::$_bCalled) {
            return;
        }
        self::$_bCalled = true;
        new yucca_shopAdminPageFramework_Form_View___Script_RegisterCallback();
        $this->_setCommonFormInlineCSSRules();
    }

    private function _setCommonFormInlineCSSRules()
    {
        $_aClassNames = ['yucca_shopAdminPageFramework_Form_View___CSS_Form', 'yucca_shopAdminPageFramework_Form_View___CSS_Field', 'yucca_shopAdminPageFramework_Form_View___CSS_Section', 'yucca_shopAdminPageFramework_Form_View___CSS_CollapsibleSection', 'yucca_shopAdminPageFramework_Form_View___CSS_FieldError', 'yucca_shopAdminPageFramework_Form_View___CSS_ToolTip'];
        foreach ($_aClassNames as $_sClassName) {
            $_oCSS = new $_sClassName();
            $this->aResources['inline_styles'][] = $_oCSS->get();
        }
        $_aClassNamesForIE = ['yucca_shopAdminPageFramework_Form_View___CSS_CollapsibleSectionIE'];
        foreach ($_aClassNames as $_sClassName) {
            $_oCSS = new $_sClassName();
            $this->aResources['inline_styles_ie'][] = $_oCSS->get();
        }
    }

    protected function _set()
    {
        foreach ($this->aFieldsets as $_sSecitonID => $_aFieldsets) {
            $_bIsSubSectionLoaded = false;
            foreach ($_aFieldsets as $_iSubSectionIndexOrFieldID => $_aSubSectionOrField) {
                if ($this->isNumericInteger($_iSubSectionIndexOrFieldID)) {
                    if ($_bIsSubSectionLoaded) {
                        continue;
                    }
                    $_bIsSubSectionLoaded = true;
                    foreach ($_aSubSectionOrField as $_aField) {
                        $this->_setFieldResources($_aField);
                    }
                    continue;
                }
                $_aField = $_aSubSectionOrField;
                $this->_setFieldResources($_aField);
            }
        }
    }

    private function _setFieldResources(array $aFieldset)
    {
        if (!$this->_isFieldsetAllowed($aFieldset)) {
            return;
        }
        $_sFieldtype = $this->getElement($aFieldset, 'type');
        $_aFieldTypeDefinition = $this->getElementAsArray($this->aFieldTypeDefinitions, $_sFieldtype);
        if (empty($_aFieldTypeDefinition)) {
            return;
        }
        if (is_callable($_aFieldTypeDefinition['hfDoOnRegistration'])) {
            call_user_func_array($_aFieldTypeDefinition['hfDoOnRegistration'], [$aFieldset]);
        }
        $this->callBack($this->aCallbacks['load_fieldset_resource'], [$aFieldset]);
        if ($this->_isAlreadyRegistered($_sFieldtype, $this->aArguments['structure_type'])) {
            return;
        }
        new yucca_shopAdminPageFramework_Form_Model___FieldTypeRegistration($_aFieldTypeDefinition, $this->aArguments['structure_type']);
        $_oFieldTypeResources = new yucca_shopAdminPageFramework_Form_Model___FieldTypeResource($_aFieldTypeDefinition, $this->aResources);
        $this->aResources = $_oFieldTypeResources->get();
    }

    private function _isAlreadyRegistered($sFieldtype, $sStructureType)
    {
        if (isset(self::$_aRegisteredFieldTypes[$sFieldtype.'_'.$sStructureType])) {
            return true;
        }
        self::$_aRegisteredFieldTypes[$sFieldtype.'_'.$sStructureType] = true;

        return false;
    }

    private static $_aRegisteredFieldTypes = [];

    private function _isFieldsetAllowed(array $aFieldset)
    {
        return $this->callBack($this->aCallbacks['is_fieldset_registration_allowed'], [true, $aFieldset]);
    }
}
