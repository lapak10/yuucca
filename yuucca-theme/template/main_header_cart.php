<?php 
$prefix = get_product_prefix();
//global $prefix;
//Lets fetch the current session variable (i.e our shopping CART
$wp_session = get_cart();
//global $wp_session;
$total_cart_price=0;

$number_of_product_in_cart = get_count_products_cart( $wp_session );




if($number_of_product_in_cart): ?>



            <!-- Shopping cart menu: style can be found in dropdown.less-->
              <li class="dropdown messages-menu">
                <!-- Menu toggle button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-shopping-cart fa-lg "></i>
                  <span class="label label-warning"><?php echo $number_of_product_in_cart; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have <?php echo $number_of_product_in_cart; ?> product in you cart</li>
                  <li>
                    <!-- inner menu: contains the messages -->
                    <ul class="menu">
                      
                      
                      <?php 

                        
                      foreach($wp_session as $key=>$value): 
                        $key_temp='';

                      if (substr($key, 0, strlen($prefix)) == $prefix) {
                           $key_temp = substr($key, strlen($prefix));
                        }


                       ?>




                      <li><!-- start message -->
                        <a href="<?php echo get_post_permalink($key_temp); ?>">

                          <div class="pull-left">
                            <!-- User Image -->
                            <img src="http://placehold.it/40x40<?php //echo get_post_meta( $key_temp , 'product_featured_image',true ); ?>" class="img-circle" alt="User Image">
                          </div>
                          <!-- Message title and timestamp -->
                          <h4>
                            <?php echo $value['name']; ?>
                            <small><i class="fa fa-at fa-lg"></i> <b>vijay</b> </small>
                          </h4>
                          <!-- The message -->
                          <p><?php echo 'Rs '.$value['price']; ?></p>
                        </a>
                      </li><!-- end message -->
                      <?php $total_cart_price += (int)$value['price']*(int)$value['quantity']; ?>
                     <?php endforeach; ?>

                    </ul><!-- /.menu -->
                  </li>
                  <li class="footer"><button onclick="window.open('<?php echo get_permalink(get_page_by_title('Checkout')); ?>','_self')" style="display: block; width: 100%;" class='btn btn-warning btn-flat'>proceed to checkout (&#8377; <?php echo $total_cart_price; ?>)</button></li>
                </ul>
              </li><!-- /.shopping-cart-menu -->
              <?php endif; ?>