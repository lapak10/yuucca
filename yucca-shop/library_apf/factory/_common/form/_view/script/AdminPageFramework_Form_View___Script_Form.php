<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_View___Script_Form extends yucca_shopAdminPageFramework_Form_View___Script_Base
{
    public static function getScript()
    {
        return <<<'JAVASCRIPTS'
( function( $ ) {

    var _removeyucca_shopAdminPageFrameworkLoadingOutputs = function() {

        jQuery( '.yucca-shop-form-loading' ).remove();
        jQuery( '.yucca-shop-form-js-on' )
            .hide()
            .css( 'visibility', 'visible' )
            .fadeIn( 200 )
            .removeClass( '.yucca-shop-form-js-on' );
    
    }

    /**
     * When some plugins or themes have JavaScript errors and the script execution gets stopped,
     * remove the style that shows "Loading...".
     */
    var _oneerror = window.onerror;
    window.onerror = function(){
        
        // We need to show the form.
        _removeyucca_shopAdminPageFrameworkLoadingOutputs();
        
        // Restore the original
        window.onerror = _oneerror;
        
        // If the original object is a function, execute it;
        // otherwise, discontinue the script execution and show the error message in the console.
        return "function" === typeof _oneerror
            ? _oneerror()      
            : false; 
       
    }
    
    /**
     * Rendering forms is heavy and unformatted layouts will be hidden with a script embedded in the head tag.
     * Now when the document is ready, restore that visibility state so that the form will appear.
     */
    jQuery( document ).ready( function() {
        _removeyucca_shopAdminPageFrameworkLoadingOutputs();
    });    

    /**
     * Gets triggered when a widget of the framework is saved.
     * @since    3.7.0
     */
    $( document ).bind( 'admin_page_framework_saved_widget', function( event, oWidget ){
        jQuery( '.yucca-shop-form-loading' ).remove();
    });    
    
}( jQuery ));
JAVASCRIPTS;
    }

    private static $_bLoadedTabEnablerScript = false;
}
