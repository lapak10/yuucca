<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_FieldType_radio extends yucca_shopAdminPageFramework_FieldType
{
    public $aFieldTypeSlugs = ['radio'];
    protected $aDefaultKeys = ['label' => [], 'attributes' => []];

    protected function getStyles()
    {
        return ".yucca-shop-field input[type='radio'] {margin-right: 0.5em;} .yucca-shop-field-radio .yucca-shop-input-label-container {padding-right: 1em;} .yucca-shop-field-radio .yucca-shop-input-container {display: inline;} .yucca-shop-field-radio .yucca-shop-input-label-string{display: inline; }";
    }

    protected function getScripts()
    {
        return '';
    }

    protected function getField($aField)
    {
        $_aOutput = [];
        foreach ($this->getAsArray($aField['label']) as $_sKey => $_sLabel) {
            $_aOutput[] = $this->_getEachRadioButtonOutput($aField, $_sKey, $_sLabel);
        }
        $_aOutput[] = $this->_getUpdateCheckedScript($aField['input_id']);

        return implode(PHP_EOL, $_aOutput);
    }

    private function _getEachRadioButtonOutput(array $aField, $sKey, $sLabel)
    {
        $_aAttributes = $aField['attributes'] + $this->getElementAsArray($aField, ['attributes', $sKey]);
        $_oRadio = new yucca_shopAdminPageFramework_Input_radio($_aAttributes);
        $_oRadio->setAttributesByKey($sKey);
        $_oRadio->setAttribute('data-default', $aField['default']);

        return $this->getElementByLabel($aField['before_label'], $sKey, $aField['label'])."<div class='yucca-shop-input-label-container yucca-shop-radio-label' style='min-width: ".$this->sanitizeLength($aField['label_min_width']).";'>".'<label '.$this->getAttributes(['for' => $_oRadio->getAttribute('id'), 'class' => $_oRadio->getAttribute('disabled') ? 'disabled' : null]).'>'.$this->getElementByLabel($aField['before_input'], $sKey, $aField['label']).$_oRadio->get($sLabel).$this->getElementByLabel($aField['after_input'], $sKey, $aField['label']).'</label>'.'</div>'.$this->getElementByLabel($aField['after_label'], $sKey, $aField['label']);
    }

    private function _getUpdateCheckedScript($sInputID)
    {
        $_sScript = <<<JAVASCRIPTS
jQuery( document ).ready( function(){
    jQuery( 'input[type=radio][data-id=\"{$sInputID}\"]' ).change( function() {
        // Uncheck the other radio buttons
        jQuery( this ).closest( '.yucca-shop-field' ).find( 'input[type=radio][data-id=\"{$sInputID}\"]' ).attr( 'checked', false );

        // Make sure the clicked item is checked
        jQuery( this ).attr( 'checked', 'checked' );
    });
});                 
JAVASCRIPTS;

        return "<script type='text/javascript' class='radio-button-checked-attribute-updater'>".'/* <![CDATA[ */'.$_sScript.'/* ]]> */'.'</script>';
    }
}
