<?php 
if( !isset($_REQUEST['_wpnonce']) OR !wp_verify_nonce($_REQUEST['_wpnonce'],'farji_modal_update_form')){
	wp_die('Nonce failure');
}

$wp_session = get_cart();



	foreach($wp_session as $key => $value){

			$value['quantity']= (int)$_POST[$key];
		//print_r($value);


		// $wp_session[$key]['quantity'] = (int)$_POST[$key];
		// //echo $key.'->'.$value['quantity'];
		// $wp_session[$key]['name'] = 'anand';
}
// print_r($wp_session);
// echo 'anand <br><hr>';
// print_r(get_cart());
//wp_redirect( get_permalink ( get_page_by_path( 'Checkout' ))  );
