<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class yucca_shopAdminPageFramework_FieldType_Base extends yucca_shopAdminPageFramework_FrameworkUtility
{
    public $_sFieldSetType = '';
    public $aFieldTypeSlugs = ['default'];
    protected $aDefaultKeys = [];
    protected static $_aDefaultKeys = ['value' => null, 'default' => null, 'repeatable' => false, 'sortable' => false, 'label' => '', 'delimiter' => '', 'before_input' => '', 'after_input' => '', 'before_label' => null, 'after_label' => null, 'before_field' => null, 'after_field' => null, 'label_min_width' => 140, 'before_fieldset' => null, 'after_fieldset' => null, 'field_id' => null, 'page_slug' => null, 'section_id' => null, 'before_fields' => null, 'after_fields' => null, 'attributes' => ['disabled' => null, 'class' => '', 'fieldrow' => [], 'fieldset' => [], 'fields' => [], 'field' => []]];
    protected $oMsg;

    public function __construct($asClassName = 'admin_page_framework', $asFieldTypeSlug = null, $oMsg = null, $bAutoRegister = true)
    {
        $this->aFieldTypeSlugs = empty($asFieldTypeSlug) ? $this->aFieldTypeSlugs : (array) $asFieldTypeSlug;
        $this->oMsg = $oMsg ? $oMsg : yucca_shopAdminPageFramework_Message::getInstance();
        if ($bAutoRegister) {
            foreach ((array) $asClassName as $_sClassName) {
                add_filter('field_types_'.$_sClassName, [$this, '_replyToRegisterInputFieldType']);
            }
        }
        $this->construct();
    }

    protected function construct()
    {
    }

    protected function isTinyMCESupported()
    {
        return version_compare($GLOBALS['wp_version'], '3.3', '>=') && function_exists('wp_editor');
    }

    protected function getElementByLabel($asElement, $asKey, $asLabel)
    {
        if (is_scalar($asElement)) {
            return $asElement;
        }

        return is_array($asLabel) ? $this->getElement($asElement, $this->getAsArray($asKey, true), '') : $asElement;
    }

    protected function geFieldOutput(array $aFieldset)
    {
        if (!is_object($aFieldset['_caller_object'])) {
            return '';
        }
        $aFieldset['_nested_depth']++;
        $aFieldset['_parent_field_object'] = $aFieldset['_field_object'];
        $_oCallerForm = $aFieldset['_caller_object'];
        $_oFieldset = new yucca_shopAdminPageFramework_Form_View___Fieldset($aFieldset, $_oCallerForm->aSavedData, $_oCallerForm->getFieldErrors(), $_oCallerForm->aFieldTypeDefinitions, $_oCallerForm->oMsg, $_oCallerForm->aCallbacks);

        return $_oFieldset->get();
    }

    public function _replyToRegisterInputFieldType($aFieldDefinitions)
    {
        foreach ($this->aFieldTypeSlugs as $sFieldTypeSlug) {
            $aFieldDefinitions[$sFieldTypeSlug] = $this->getDefinitionArray($sFieldTypeSlug);
        }

        return $aFieldDefinitions;
    }

    public function getDefinitionArray($sFieldTypeSlug = '')
    {
        $_aDefaultKeys = $this->aDefaultKeys + self::$_aDefaultKeys;
        $_aDefaultKeys['attributes'] = isset($this->aDefaultKeys['attributes']) && is_array($this->aDefaultKeys['attributes']) ? $this->aDefaultKeys['attributes'] + self::$_aDefaultKeys['attributes'] : self::$_aDefaultKeys['attributes'];

        return ['sFieldTypeSlug' => $sFieldTypeSlug, 'aFieldTypeSlugs' => $this->aFieldTypeSlugs, 'hfRenderField' => [$this, '_replyToGetField'], 'hfGetScripts' => [$this, '_replyToGetScripts'], 'hfGetStyles' => [$this, '_replyToGetStyles'], 'hfGetIEStyles' => [$this, '_replyToGetInputIEStyles'], 'hfFieldLoader' => [$this, '_replyToFieldLoader'], 'hfFieldSetTypeSetter' => [$this, '_replyToFieldTypeSetter'], 'hfDoOnRegistration' => [$this, '_replyToDoOnFieldRegistration'], 'aEnqueueScripts' => $this->_replyToGetEnqueuingScripts(), 'aEnqueueStyles' => $this->_replyToGetEnqueuingStyles(), 'aDefaultKeys' => $_aDefaultKeys];
    }

    public function _replyToGetField($aField)
    {
        return '';
    }

    public function _replyToGetScripts()
    {
        return '';
    }

    public function _replyToGetInputIEStyles()
    {
        return '';
    }

    public function _replyToGetStyles()
    {
        return '';
    }

    public function _replyToFieldLoader()
    {
    }

    public function _replyToFieldTypeSetter($sFieldSetType = '')
    {
        $this->_sFieldSetType = $sFieldSetType;
    }

    public function _replyToDoOnFieldRegistration(array $aField)
    {
    }

    protected function _replyToGetEnqueuingScripts()
    {
        return [];
    }

    protected function _replyToGetEnqueuingStyles()
    {
        return [];
    }

    protected function enqueueMediaUploader()
    {
        add_filter('media_upload_tabs', [$this, '_replyToRemovingMediaLibraryTab']);
        wp_enqueue_script('jquery');
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
        if (function_exists('wp_enqueue_media')) {
            new yucca_shopAdminPageFramework_Form_View___Script_MediaUploader($this->oMsg);
        } else {
            wp_enqueue_script('media-upload');
        }
        if (in_array($this->getPageNow(), ['media-upload.php', 'async-upload.php'])) {
            add_filter('gettext', [$this, '_replyToReplaceThickBoxText'], 1, 2);
        }
    }

    public function _replyToReplaceThickBoxText($sTranslated, $sText)
    {
        if (!in_array($this->getPageNow(), ['media-upload.php', 'async-upload.php'])) {
            return $sTranslated;
        }
        if ($sText !== 'Insert into Post') {
            return $sTranslated;
        }
        if ($this->getQueryValueInURLByKey(wp_get_referer(), 'referrer') !== 'admin_page_framework') {
            return $sTranslated;
        }
        if (isset($_GET['button_label'])) {
            return $_GET['button_label'];
        }

        return $this->oProp->sThickBoxButtonUseThis ? $this->oProp->sThickBoxButtonUseThis : $this->oMsg->get('use_this_image');
    }

    public function _replyToRemovingMediaLibraryTab($aTabs)
    {
        if (!isset($_REQUEST['enable_external_source'])) {
            return $aTabs;
        }
        if (!$_REQUEST['enable_external_source']) {
            unset($aTabs['type_url']);
        }

        return $aTabs;
    }
}
abstract class yucca_shopAdminPageFramework_FieldType extends yucca_shopAdminPageFramework_FieldType_Base
{
    public function _replyToFieldLoader()
    {
        $this->setUp();
    }

    public function _replyToGetScripts()
    {
        return $this->getScripts();
    }

    public function _replyToGetInputIEStyles()
    {
        return $this->getIEStyles();
    }

    public function _replyToGetStyles()
    {
        return $this->getStyles();
    }

    public function _replyToGetField($aField)
    {
        return $this->getField($aField);
    }

    public function _replyToDoOnFieldRegistration(array $aField)
    {
        return $this->doOnFieldRegistration($aField);
    }

    protected function _replyToGetEnqueuingScripts()
    {
        return $this->getEnqueuingScripts();
    }

    protected function _replyToGetEnqueuingStyles()
    {
        return $this->getEnqueuingStyles();
    }

    public $aFieldTypeSlugs = ['default'];
    protected $aDefaultKeys = [];

    protected function construct()
    {
    }

    protected function setUp()
    {
    }

    protected function getScripts()
    {
        return '';
    }

    protected function getIEStyles()
    {
        return '';
    }

    protected function getStyles()
    {
        return '';
    }

    protected function getField($aField)
    {
        return '';
    }

    protected function getEnqueuingScripts()
    {
        return [];
    }

    protected function getEnqueuingStyles()
    {
        return [];
    }

    protected function doOnFieldRegistration($aField)
    {
    }
}
class yucca_shopAdminPageFramework_FieldType_color extends yucca_shopAdminPageFramework_FieldType
{
    public $aFieldTypeSlugs = ['color'];
    protected $aDefaultKeys = ['attributes' => ['size' => 10, 'maxlength' => 400, 'value' => 'transparent']];

    protected function setUp()
    {
        if (version_compare($GLOBALS['wp_version'], '3.5', '>=')) {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
        } else {
            wp_enqueue_style('farbtastic');
            wp_enqueue_script('farbtastic');
        }
    }

    protected function getStyles()
    {
        return '.repeatable .colorpicker {display: inline;}.yucca-shop-field-color .wp-picker-container {vertical-align: middle;}.yucca-shop-field-color .ui-widget-content {border: none;background: none;color: transparent;}.yucca-shop-field-color .ui-slider-vertical {width: inherit;height: auto;margin-top: -11px;}.yucca-shop-field-color .yucca-shop-repeatable-field-buttons {margin-top: 0;}.yucca-shop-field-color .wp-color-result {margin: 3px;}';
    }

    protected function getScripts()
    {
        $_aJSArray = json_encode($this->aFieldTypeSlugs);
        $_sDoubleQuote = '\"';

        return <<<JAVASCRIPTS
registeryucca_shopAdminPageFrameworkColorPickerField = function( osTragetInput, aOptions ) {
    
    var osTargetInput   = 'string' === typeof osTragetInput 
        ? '#' + osTragetInput 
        : osTragetInput;
    var sInputID        = 'string' === typeof osTragetInput 
        ? osTragetInput 
        : osTragetInput.attr( 'id' );

    // Only for the iris color picker.
    var _aDefaults = {
        defaultColor: false, // you can declare a default color here, or in the data-default-color attribute on the input     
        change: function(event, ui){}, // a callback to fire whenever the color changes to a valid color. reference : http://automattic.github.io/Iris/     
        clear: function() {}, // a callback to fire when the input is emptied or an invalid color
        hide: true, // hide the color picker controls on load
        palettes: true // show a group of common colors beneath the square or, supply an array of colors to customize further                
    };
    var _aColorPickerOptions = jQuery.extend( {}, _aDefaults, aOptions );
        
    'use strict';
    /* This if-statement checks if the color picker element exists within jQuery UI
     If it does exist, then we initialize the WordPress color picker on our text input field */
    if( 'object' === typeof jQuery.wp && 'function' === typeof jQuery.wp.wpColorPicker ){
        jQuery( osTargetInput ).wpColorPicker( _aColorPickerOptions );
    }
    else {
        /* We use farbtastic if the WordPress color picker widget doesn't exist */
        jQuery( '#color_' + sInputID ).farbtastic( osTargetInput );
    }
}

/* The below function will be triggered when a new repeatable field is added. Since the APF repeater script does not
    renew the color piker element (while it does on the input tag value), the renewal task must be dealt here separately. */
jQuery( document ).ready( function(){
    
    jQuery().registeryucca_shopAdminPageFrameworkCallbacks( {     
        added_repeatable_field: function( node, sFieldType, sFieldTagID, sCallType ) {

            /* If it is not the color field type, do nothing. */
            // if ( jQuery.inArray( sFieldType, $_aJSArray ) <= -1 ) { 
                // return; 
            // }
            
            /* If the input tag is not found, do nothing  */
            var nodeNewColorInput = node.find( 'input.input_color' );
            if ( nodeNewColorInput.length <= 0 ) { 
                return; 
            }
            
            var nodeIris = node.find( '.wp-picker-container' ).first();
            // WP 3.5+
            if ( nodeIris.length > 0 ) { 
                // unbind the existing color picker script in case there is.
                var nodeNewColorInput = nodeNewColorInput.clone(); 
            }
            var sInputID = nodeNewColorInput.attr( 'id' );

            // Reset the value of the color picker
            var sInputValue = nodeNewColorInput.val() 
                ? nodeNewColorInput.val() 
                : nodeNewColorInput.attr( 'data-default' );
            var sInputStyle = sInputValue !== 'transparent' && nodeNewColorInput.attr( 'style' )
                ? nodeNewColorInput.attr( 'style' ) 
                : '';
            nodeNewColorInput.val( sInputValue ); // set the default value    
            nodeNewColorInput.attr( 'style', sInputStyle ); // remove the background color set to the input field ( for WP 3.4.x or below )  

            // Replace the old color picker elements with the new one.
            // WP 3.5+
            if ( nodeIris.length > 0 ) { 
                jQuery( nodeIris ).replaceWith( nodeNewColorInput );
            } 
            // WP 3.4.x -     
            else { 
                node.find( '.colorpicker' ).replaceWith( '<div class=\"colorpicker\" id=\"color_' + sInputID + '\"></div>' );
            }

            // Bind the color picker event.
            registeryucca_shopAdminPageFrameworkColorPickerField( nodeNewColorInput );     
            
        }
    },
    {$_aJSArray}
    );
});
JAVASCRIPTS;
    }

    protected function getField($aField)
    {
        $aField['value'] = is_null($aField['value']) ? 'transparent' : $aField['value'];
        $aField['attributes'] = $this->_getInputAttributes($aField);

        return $aField['before_label']."<div class='yucca-shop-input-label-container'>"."<label for='{$aField['input_id']}'>".$aField['before_input'].($aField['label'] && !$aField['repeatable'] ? "<span class='yucca-shop-input-label-string' style='min-width:".$this->sanitizeLength($aField['label_min_width']).";'>".$aField['label'].'</span>' : '').'<input '.$this->getAttributes($aField['attributes']).' />'.$aField['after_input']."<div class='repeatable-field-buttons'></div>".'</label>'."<div class='colorpicker' id='color_{$aField['input_id']}'></div>".$this->_getColorPickerEnablerScript("{$aField['input_id']}").'</div>'.$aField['after_label'];
    }

    private function _getInputAttributes(array $aField)
    {
        return ['color' => $aField['value'], 'value' => $aField['value'], 'data-default' => isset($aField['default']) ? $aField['default'] : 'transparent', 'type' => 'text', 'class' => trim('input_color '.$aField['attributes']['class'])] + $aField['attributes'];
    }

    private function _getColorPickerEnablerScript($sInputID)
    {
        $_sScript = <<<JAVASCRIPTS
jQuery( document ).ready( function(){
    registeryucca_shopAdminPageFrameworkColorPickerField( '{$sInputID}' );
});            
JAVASCRIPTS;

        return "<script type='text/javascript' class='color-picker-enabler-script'>".'/* <![CDATA[ */'.$_sScript.'/* ]]> */'.'</script>';
    }
}
