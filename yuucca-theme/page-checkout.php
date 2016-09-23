<?php
//global $wp_session;
//global $prefix;


$prefix = get_product_prefix();


//console_log($_POST);
if (isset($_POST['submit']) and $_POST['submit'] == 'save_new_order' and wp_verify_nonce($_REQUEST['_wpnonce'], 'page-checkout-place-order-form')) {
    $wp_session = get_cart();
// console_log("POST variable");
// console_log($_POST);
// console_log("our cart");
// console_log($wp_session);
// console_log('here we need to do the saving process of new order');
$delivery_address = [
  'name'  => $_POST['customer_name'],
  'flat'  => $_POST['customer_flat'],
  'tower' => $_POST['customer_tower'],

  'mobile' => $_POST['customer_mobile'],
  ];




    $post_id = wp_insert_post(['post_type' => 'yuccasay_order']);

    $order_content = json_encode($wp_session);

    $my_post = [
     'post_title' => 'Order #'.$post_id,
     //'post_date' => '',
     'post_content' => $order_content,
     'post_status'  => 'publish',
     //'post_type' => 'yuccasay_order',
  ];


    console_log($post_id);





    add_post_meta($post_id, 'delivery_address_name', $_POST['customer_name'], true);
    add_post_meta($post_id, 'delivery_address_flat', $_POST['customer_flat'], true);
    add_post_meta($post_id, 'delivery_address_tower', $_POST['customer_tower'], true);
    add_post_meta($post_id, 'delivery_address_mobile', $_POST['customer_mobile'], true);

    wp_update_post(array_merge(['ID' => $post_id], $my_post));


    shoot_mail($order_content);
    destroy_my_cart();

 //update_post_meta($post_id, 'META-KEY-2', 'META_VALUE-2', true
 // if($post_id){
 //  destroy_my_cart();
 // }
}



if (isset($_POST['submit']) and wp_verify_nonce($_REQUEST['_wpnonce'], 'farji_modal_update_form')) {
    $wp_session = get_cart();

    foreach ($wp_session as $key => $value) {

    //unset($value['quantity']);
    //unset($value['price']);
      //$value['quantity'] = (int)$_POST[$key];
      //$_SESSION['anand_store'][$key]['quantity'] = 3;
      //$value['price'] = (int)$_POST[$key];
    //print_r($value);


     $_SESSION['anand_store'][$key]['quantity'] = (int) $_POST[$key];

        echo "<script type='text/javascript'>console.log('The quantity of $key is now set to $_POST[$key]')</script>";
    // //echo $key.'->'.$value['quantity'];
    // $wp_session[$key]['name'] = 'anand';
    }
//$_SESSION['anand_store'] = $wp_session;
//print_r($wp_session);
}
//echo $wp_session->json_out();



$wp_session = get_cart();
if (!get_count_products_cart()) {
    wp_redirect(get_permalink(get_page_by_path('empty-cart')));
}

?> 
<?php get_template_part('template/header'); ?>
<?php get_template_part('template/main_header'); ?>
<?php get_template_part('template/left_nav_menu'); ?>
     

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) 
        <section class="content-header">
          <h1>
            Checkout Page 
            <small>Optional description</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
          </ol>
        </section>
-->
        <!-- Main content -->
        <section class="content">


		<div class="row">
		<div class="col-md-8">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">order list</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered">
                <tbody>
                <tr class="hidden-sm hidden-xs visible-md visible-lg">
                  <th>#</th>
                  <th>product</th>
                  <th>status</th>
                  <th>price</th>
                  <th>quantity</th>
                  <th>amount</th>
                </tr>

                <tr class="visible-xs visible-sm hidden-md hidden-lg">
                  <th>#</th>
                  <th>prod.</th>
                  <th>stats.</th>
                  <th>pric.</th>
                  <th>qnty</th>
                  <th>amnt</th>
                </tr>


					<?php 

                    $count = 0;
          $key_temp = '';
          //var_dump($wp_session);
                    foreach ($wp_session as $key => $value):
                        $count++;


                    if (substr($key, 0, strlen($prefix)) == $prefix) {
                        $key_temp = substr($key, strlen($prefix));
                    }

                    ?>
                      






                <tr>
                  <td><?php echo $count; ?></td>
                  <td><img style="margin: auto 10px auto auto; width: 50px; height: 50px;" src="http://placehold.it/350x150<?php //echo get_post_meta( $key_temp , 'product_featured_image',true );?>" class='img-cicle' alt="Product Image">
                <a href="<?php echo get_post_permalink($key_temp); ?>"><strong><?php echo $value['name']; ?></strong></a></td>
                  <td >
                    <div data-toggle="tooltip" title="Delivery boy is in the way!" class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                    </div>
                  </td>
                  <td><?php echo 'Rs '.$value['price']; ?></td>
                  <td><?php echo $value['quantity']; //$value['quantity'];?></td>
                  <td><?php echo 'Rs '.(int) $value['quantity'] * (int) $value['price']; ?></td>
                  </tr>
              
				<?php endforeach; ?>
              </tbody>
              </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
               <div class="pull-right">
  				<!-- <button type="button" class="btn btn-warning "><span class="glyphicon glyphicon-ok"></span> place order</button> -->
 				 
 					 <button type="button" class="btn btn-flat btn-primary" data-toggle='modal' data-target='#edit_cart' ><span class="glyphicon glyphicon-edit"></span> edit cart</button>
</div>
<small class='pull left'><?php echo apply_filters('checkout_terms_message', 'by clicking place order you agree our <a href="">terms and conditions.</a>'); ?></small>

           </div>
          </div>
          <!-- /.box -->

          </div>

        
          
          <div class="col-md-4">

            <form action="" method='POST'>
            <?php wp_nonce_field('page-checkout-place-order-form'); ?>

          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">delivery address</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                </div>
            </div>
            <div class="box-body">
              
              <div class="form-group">
              <input name="customer_name" class="form-control input-lg" placeholder="your name?" type="text">
              <br>
              <input name="customer_flat" class="form-control input-lg" placeholder="flat and tower" type="text">
              <br>
                    <label>society</label>
                    <select name="customer_tower" class="form-control select2 input-lg" style="width: 100%;">
                      <option selected="selected">Alabama</option>
                      <option>Alaska</option>
                      <option>California</option>
                      <option>Delaware</option>
                      <option>Tennessee</option>
                      <option>Texas</option>
                      <option>Washington</option>
                    </select>
              <br>
              <input name="customer_mobile" class="form-control input-lg" placeholder="mobile no." type="number">
              </div><!-- /.form-group -->
            </div>
            <div class="box-footer clearfix">
              
              <button type='submit' name='submit' value='save_new_order' class="btn btn-warning btn-flat btn-block btn-lg pull-right"><span class="glyphicon glyphicon-ok"></span> place order</button>	
          
            </form>

           </div>
          </div>
          <!-- /.box -->
          </div>

          </div>
				
          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
<?php get_template_part('template/farji_modal'); ?>
<?php get_template_part('template/main_footer'); ?>