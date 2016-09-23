<?php

/**
 <http://en.michaeluno.jp/yucca-shop>
 Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yucca_shopAdminPageFramework_Form_View___Script_OptionStorage extends yucca_shopAdminPageFramework_Form_View___Script_Base
{
    public static function getScript()
    {
        return <<<JAVASCRIPTS
(function ( $ ) {
            
    $.fn.ayucca_shopAdminPageFrameworkInputOptions = {}; 
                            
    $.fn.storeyucca_shopAdminPageFrameworkInputOptions = function( sID, vOptions ) {
        var sID = sID.replace( /__\d+_/, '___' );	// remove the section index. The g modifier is not used so it will replace only the first occurrence.
        $.fn.ayucca_shopAdminPageFrameworkInputOptions[ sID ] = vOptions;
    };	
    $.fn.getyucca_shopAdminPageFrameworkInputOptions = function( sID ) {
        var sID = sID.replace( /__\d+_/, '___' ); // remove the section index
        return ( 'undefined' === typeof $.fn.ayucca_shopAdminPageFrameworkInputOptions[ sID ] )
            ? null
            : $.fn.ayucca_shopAdminPageFrameworkInputOptions[ sID ];
    }

}( jQuery ));
JAVASCRIPTS;
    }
}
