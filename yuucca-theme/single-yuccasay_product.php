<?php
//a basic key to check whether the product one the screen is added to the cart or not..
// FALSE for default
$is_added_to_cart = false;

//Lets fetch the current session variable (i.e our shopping CART)
//global $wp_session;
$wp_session = get_cart();
//global $prefix;
$prefix = get_product_prefix();
//Prepare our custom key for the session array
$str = $prefix.get_the_ID();

    //check if the product is added in the cart already??
    if (isset($wp_session[$str])) {
        //ok , the product is present in our cart..lets make the key reflect the state of product in cart
$is_added_to_cart = true;
    }

    //check is the form is submitted or not ...and if yes....is it to add a product...or remove a product from the cart?
    if (isset($_POST['submit']) && $_POST['submit'] == 'add_this_to_cart') {
        //ok, the request is to add the product into the cart...lets do it!
      $_SESSION['anand_store'][$str] = [
                                  'name'  => get_the_title(get_the_id()),
                                  'price' => get_post_meta(get_the_ID(), 'product_price', true),
                    'quantity'            => 1,

                          ];

    ////ok , the product is present in our cart..lets make the key reflect the state of product in cart
      $is_added_to_cart = true;
    }


    //check is the form is submitted or not ...and if yes....is it to add a product...or remove a product from the cart?
    if (isset($_POST['submit']) && $_POST['submit'] == 'remove_this_from_cart') {

      //ok, the request is to REMOVE the product from the cart...lets do it!
      unset($_SESSION['anand_store'][$str]);

      //ok , the product is REMOVED from our cart..lets make the key reflect the state of product in cart
      $is_added_to_cart = false;
    }

?>

<?php get_template_part('template/header'); ?>
<?php get_template_part('template/main_header'); ?>
<?php get_template_part('template/left_nav_menu'); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <?php the_title(); ?>
<!--             <small> <?php echo get_post_meta(get_the_ID(), 'product_short_description', true); ?> </small> -->
<small><a href=""><strong>view all</strong></a></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>Level</a></li>
            <li class="active">Here</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
        <p>Price : <?php echo 'Rs '.get_post_meta(get_the_ID(), 'product_price', true); ?></p>
        <img class="img-responsive" style="
    width: 600px;
" src="http://placehold.it/350x150<?php //echo get_post_meta( get_the_ID(), 'product_featured_image',true );?>">
        <p>Brief Description : <?php echo get_post_meta(get_the_ID(), 'product_full_description', true); ?></p>
          <!-- Your Page Content Here -->
		<form action="" method="post">
		
		<?php 

        if (!$is_added_to_cart) {
            ?>

<button type="submit" value='add_this_to_cart' <?php echo $is_added_to_cart ? 'disabled' : '';
            ?> name="submit" class="btn btn-primary"><?php echo $is_added_to_cart ? 'already added' : 'add to cart';
            ?></button>

		<?php 
        } else {
            ?>
<button type="submit" value='remove_this_from_cart' name="submit" class="btn btn-danger">remove from cart</button>

		<?php 
        } ?>

<?php endwhile; else : ?>
	<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif; ?>
		
		</form>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
<?php get_template_part('template/farji_modal'); ?>



<?php get_template_part('template/main_footer'); ?>
