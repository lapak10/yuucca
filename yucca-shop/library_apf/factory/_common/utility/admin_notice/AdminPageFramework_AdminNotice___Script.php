<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_AdminNotice___Script extends yucca_shopAdminPageFramework_Factory___Script_Base
{
    public function load()
    {
        wp_enqueue_script('jquery');
    }

    public static function getScript()
    {
        return <<<'JAVASCRIPTS'
( function( $ ) {
    jQuery( document ).ready( function() {         

        var _oAdminNotices = jQuery( '.yucca-shop-settings-notice-message' );
        if ( _oAdminNotices.length ) {
                    
            // Animation of the `slideDown()` method does not work well when the target element has a margin
            // so enclose the elemnet in a new container and apply new margins to it.
            var _oContainer     = jQuery( _oAdminNotices )
                .css( 'margin', '0' )   // prevents jumpy animation
                .wrap( "<div class='yucca-shop-admin-notice-animation-container'></div>" );
            _oContainer.css( 'margin-top', '1em' );
            _oContainer.css( 'margin-bottom', '1em' );
            
            // Now animate.
            jQuery( _oAdminNotices )
                .css( 'visibility', 'hidden' )
                .slideDown( 800 )
                .css( {opacity: 0, visibility: 'visible'})
                .animate( {opacity: 1}, 400 );
                
        }

    });              

}( jQuery ));
JAVASCRIPTS;
    }
}
