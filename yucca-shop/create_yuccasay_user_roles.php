<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


$USER_ROLES_TO_DELETE = array(
	'seller_single_person'=>'Individual Seller'
	);

foreach($USER_ROLES_TO_DELETE as $key => $value){
remove_role( $key, $value);
}

$USER_ROLES_TO_ADD = array(
	'seller_single_person'=>'Individual Seller',
	'delivery_boy'=>'Delivery Boy',
	'customer_registered'=>'Registered Customer',
	'customer_guest'=>'Guest Customer'
	);

foreach($USER_ROLES_TO_ADD as $key => $value){
add_role( $key, $value);
}

function add_theme_caps() {
    // gets the author role
    $role = get_role( 'seller_single_person' );

    // This only works, because it accesses the class instance.
    // would allow the author to edit others' posts for current theme only
    $role->add_cap( 'edit_ysy_product' ); 
}
add_action( 'admin_init', 'add_theme_caps');