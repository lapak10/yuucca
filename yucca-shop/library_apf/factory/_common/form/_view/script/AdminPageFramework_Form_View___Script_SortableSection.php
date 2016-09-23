<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_View___Script_SortableField extends yucca_shopAdminPageFramework_Form_View___Script_Base
{
    public function construct()
    {
        wp_enqueue_script('jquery-ui-sortable');
    }

    public static function getScript()
    {
        return <<<'JAVASCRIPTS'
(function($) {
    $.fn.enableyucca_shopAdminPageFrameworkSortableFields = function( sFieldsContainerID ) {

        var _oTarget    = 'string' === typeof sFieldsContainerID
            ? $( '#' + sFieldsContainerID + '.sortable' )
            : this;
        
        _oTarget.unbind( 'sortupdate' );
        _oTarget.unbind( 'sortstop' );
        var _oSortable  = _oTarget.sortable(
            // the options for the sortable plugin
            { 
                items: '> div:not( .disabled )',
            } 
        );

        // Callback the registered functions.
        _oSortable.bind( 'sortstop', function() {
            $( this ).callBackStoppedSortingFields( 
                $( this ).data( 'type' ),
                $( this ).attr( 'id' ),
                0  // call type 0: fields, 1: sections
            );  
        });
        _oSortable.bind( 'sortupdate', function() {
            $( this ).callBackSortedFields( 
                $( this ).data( 'type' ),
                $( this ).attr( 'id' ),
                0  // call type 0: fields, 1: sections
            );
        });                 
    
    };
}( jQuery ));
JAVASCRIPTS;
    }
}
class yucca_shopAdminPageFramework_Form_View___Script_SortableSection extends yucca_shopAdminPageFramework_Form_View___Script_SortableField
{
    public static function getScript()
    {
        return <<<'JAVASCRIPTS'
(function($) {
    $.fn.enableyucca_shopAdminPageFrameworkSortableSections = function( sSectionsContainerID ) {

        var _oTarget    = 'string' === typeof sSectionsContainerID 
            ? $( '#' + sSectionsContainerID + '.sortable-section' )
            : this;

        // For tabbed sections, enable the sort to the tabs.
        var _bIsTabbed      = _oTarget.hasClass( 'yucca-shop-section-tabs-contents' );
        var _bCollapsible   = 0 < _oTarget.children( '.yucca-shop-section.is_subsection_collapsible' ).length;

        var _oTarget        = _bIsTabbed
            ? _oTarget.find( 'ul.yucca-shop-section-tabs' )
            : _oTarget;

        _oTarget.unbind( 'sortupdate' );
        _oTarget.unbind( 'sortstop' );
        
        var _aSortableOptions = { 
                items: _bIsTabbed
                    ? '> li:not( .disabled )'
                    : '> div:not( .disabled, .yucca-shop-collapsible-toggle-all-button-container )', 
                handle: _bCollapsible
                    ? '.yucca-shop-section-caption'
                    : false,
                
                // @todo Figure out how to allow the user to highlight text in sortable elements.
                // cancel: '.yucca-shop-section-description, .yucca-shop-section-title'
                
            }
        var _oSortable  = _oTarget.sortable( _aSortableOptions );               
        
        if ( ! _bIsTabbed ) {
            
            _oSortable.bind( 'sortstop', function() {
                                    
                jQuery( this ).find( 'caption > .yucca-shop-section-title:not(.yucca-shop-collapsible-sections-title,.yucca-shop-collapsible-section-title)' ).first().show();
                jQuery( this ).find( 'caption > .yucca-shop-section-title:not(.yucca-shop-collapsible-sections-title,.yucca-shop-collapsible-section-title)' ).not( ':first' ).hide();
                
            } );            
            
        }            
    
    };
}( jQuery ));
JAVASCRIPTS;
    }

    private static $_aSetContainerIDsForSortableSections = [];

    public static function getEnabler($sContainerTagID, $aSettings, $oMsg)
    {
        if (empty($aSettings)) {
            return '';
        }
        if (in_array($sContainerTagID, self::$_aSetContainerIDsForSortableSections)) {
            return '';
        }
        self::$_aSetContainerIDsForSortableSections[$sContainerTagID] = $sContainerTagID;
        new self($oMsg);
        $_sScript = <<<JAVASCRIPTS
jQuery( document ).ready( function() {    
    jQuery( '#{$sContainerTagID}' ).enableyucca_shopAdminPageFrameworkSortableSections( '{$sContainerTagID}' ); 
});            
JAVASCRIPTS;

        return "<script type='text/javascript' class='yucca-shop-section-sortable-script'>".'/* <![CDATA[ */'.$_sScript.'/* ]]> */'.'</script>';
    }
}
