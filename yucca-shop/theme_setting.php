<?php 
//Register my custom menu locations here....without this..there will be No APPEARANCE>MENUS.
add_action( 'init',function () {
  register_nav_menus(
    array(
      'left_aside_menu' => __( 'Main menu' )
    )
  );
});

//Small hack which changes the default active class in the menu to ACTIVE State..
add_filter('nav_menu_css_class' ,function ($classes, $item){
     
    if( in_array('current-menu-item', $classes) ){
             $classes[] = 'active ';
     }
     return $classes;

}, 10 , 2);

