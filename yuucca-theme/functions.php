<?php

include('dimox_breacrumbs.php');
show_admin_bar( false );
if ( ! function_exists( 'get_cart' ) ){

	function get_cart(){

		//Lets fetch the current session variable (i.e our shopping CART
			if(empty($_SESSION['anand_store'])){
				$_SESSION['anand_store']=array();
			}

			return $_SESSION['anand_store'];

			//$number_of_product_in_cart = count($wp_session);
	} 



}

if ( ! function_exists( 'get_count_products_cart' ) ){

	function get_count_products_cart(){

		//Lets fetch the current session variable (i.e our shopping CART
			if(empty($_SESSION['anand_store'])){
				return 0;
			}

			return count($_SESSION['anand_store']);
	} 



}

if ( ! function_exists( 'shoot_mail' ) ){

	function shoot_mail($order){

		$headers = 'From: Yuucca.com <anand@yuucca.com>' . "\r\n";
 		return wp_mail( 'anand.kmk@gmail.com', 'New Order!!', $order, $headers, $attachments );
	} 



}


if ( ! function_exists( 'get_product_prefix' ) ){

	function get_product_prefix(){

		return 'product_id_';
	} 



}


if ( ! function_exists( 'destroy_my_cart' ) ){

	function destroy_my_cart(){

		 unset($_SESSION['anand_store']);
	} 



}
/**
 * Send debug code to the Javascript console
 */ 
if ( ! function_exists( 'console_log' ) ){
function console_log($data) {
    if(is_array($data) || is_object($data))
	{
		echo("<script>console.log('PHP: ".json_encode($data)."');</script>");
	} else {
		echo("<script>console.log('PHP: ".$data."');</script>");
	}
}
}


add_filter('manage_edit-yuccasay_order_columns',function($col){

	return array_merge($col,array(
		'title' => _('Order'),
		'sa' => _('Status'),
		'anand' => _('Address'),
		'sass' => _('Total Amount'),
		//'date' => _('Date'),
		'actions' => _('Actions')

		));

});

add_action('manage_yuccasay_order_posts_custom_column',function($col){

	switch ($col) {
		case 'sass':
			echo '<b>Anand</b>';
			break;
		case 'sa':
			echo 'Processing';
		default:
			# code...
			break;
	}

});

add_filter('checkout_terms_message',function($string){

//return 'hi there '.$string;
	return $string;

});





add_action('wp_enqueue_scripts', function() {
  // enqueue jQuery and AngularJS
  wp_register_script('angular-core', '//ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular.js', array('jquery'), null, true);
  wp_register_script('angular-route', '//ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular-route.min.js', array('angular-core'), null, true);
  //wp_register_script('angular-app', get_bloginfo('template_directory').'/app.js', array('angular-core'), null, false);
	wp_deregister_script('jquery');
	wp_register_script('jquery', (get_template_directory_uri()."/plugins/jQuery/jQuery-2.1.4.min.js"), false, '',true);
	wp_enqueue_script('jquery');

	wp_register_script('anand_angular_app', (get_template_directory_uri()."/angular_app/anand_app.js"), array('angular-core'), null, true);
  // enqueue all scripts
  wp_enqueue_script('angular-core');
  wp_enqueue_script('angular-route');
  wp_enqueue_script('anand_angular_app' );
  //wp_enqueue_script('angular-app');
  //wp_enqueue_script('angular-directives');
});

add_action('init', 'myStartSession', 1);
add_action('wp_logout', 'myEndSession');
add_action('wp_login', 'myEndSession');

function myStartSession() {
    if(!session_id()) {
        session_start();
    }
}

function myEndSession() {
    session_destroy ();
}
    
 

