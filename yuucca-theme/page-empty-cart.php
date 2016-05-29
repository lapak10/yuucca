<?php get_template_part( 'template/header'); ?>
<?php get_template_part( 'template/main_header'); ?>
<?php get_template_part( 'template/left_nav_menu'); ?>

      

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header)
        <section class="content-header">
          <h1>
            Page Header
            <small>Optional description</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
          </ol>
        </section> -->

        <!-- Main content -->
        <section class="content">


<div class="error-page">
            <div class="row">
            	<h2 class="headline text-yellow col-md-2"><i class="fa fa-shopping-cart"></i> 404</h2>
            <div class="error-content col-md-10">
              <h3><i class="fa fa-warning text-yellow"></i> Oops! looks like you have nothing to order.</h3>
              <p>
                We know you like us, but first you gotta choose some product to order.
                Meanwhile, you may <a href=" <?php echo get_home_url(); ?> ">return to home</a>.
              </p>
              <!-- <form class="search-form">
                <div class="input-group">
                  <input type="text" name="search" class="form-control" placeholder="Search">
                  <div class="input-group-btn">
                    <button type="submit" name="submit" class="btn btn-danger btn-flat"><i class="fa fa-search"></i></button>
                  </div>
                </div>

              </form> -->
            </div>
          </div>
            </div>
</section><!-- /.content -->
      </div><!-- /.content-wrapper -->

<?php get_template_part( 'template/main_footer' ); ?>