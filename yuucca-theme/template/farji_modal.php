<?php
//$prefix = get_product_prefix();
global $prefix;
//Lets fetch the current session variable (i.e our shopping CART
//$wp_session = get_cart();
global $wp_session;

//ANGULAR -true
// ng-app='farji_modal'

?>
<div ng-controller="farji_modal" class="modal fade" id='edit_cart' tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">edit cart <span class="modal_total_amount"></span></h4>
      </div>
      <div class="modal-body">
        
<!-- table starts here -->
            <div class="box-body">
              <table class="table table-bordered" id='farji_modal_table'>
                <tbody>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>product</th>
                  <th>quantity</th>
                  <th>amount</th>
                  <th>actions</th>
                </tr>


          <?php 

          $count = 0;

          foreach ($wp_session as $key => $value):
            $count++;

          if (substr($key, 0, strlen($prefix)) == $prefix) {
              $key = substr($key, strlen($prefix));
          }

          ?>
                      



          <form action="" id='farji_modal_form' method='post'>
          <tr name='<?php echo $prefix.$key; ?>' class='product_tr'>
                  <td name='count'><?php echo $count; ?></td>
                  <td><a href="<?php echo get_post_permalink($key); ?>"><strong><?php echo $value['name']; ?></strong></a></td>
                  <td><span class='quantity'><?php echo $value['quantity']; ?></span> x <span class='price'><?php echo $value['price']; ?></span></td>
                  <td><span class='total_amount'><?php echo (int) $value['quantity'] * (int) $value['price']; ?></span></td>
                  <td>
                    <div class="btn-group" role="group" aria-label="...">
  <button type="button" class="btn btn-default plus_one">+1</button>
  
  <button type="button" class="btn btn-default minus_one">-1</button>
</div>
<div class="btn-group" role="group" aria-label="...">
  <button type="button" class="btn btn-danger">delete</button>
  
  
</div>
                                      </td>
                <input type="hidden" class='product_quantity_hidden' name='<?php echo $prefix.$key; ?>' value='1'></input>
                </tr>
                
                
          
                
              
        <?php endforeach; ?>

        <?php wp_nonce_field('farji_modal_update_form'); ?>
        

              </tbody>
              </table>
            </div>
<!-- table ends here -->



      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
        <button type="submit"  name='submit' class="btn btn-primary pull-right hidden-sm hidden-xs visible-md visible-lg">update cart</button>
        <button type="submit"  name='submit' class="btn btn-primary btn-block visible-xs visible-sm hidden-md hidden-lg">update cart</button>
        </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->