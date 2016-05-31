<?php get_template_part('template/header'); ?>
<?php get_template_part('template/main_header'); ?>
<?php get_template_part('template/left_nav_menu'); ?>

      

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header)
        <section class="content-header">
          <h1>
            Archive Page
            <small>all products</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
          </ol>
        </section>  -->

        <!-- Main content -->
        <section class="content">

          <!-- Your Page Content Here -->
        <?php

         $array = [
'<i class="fa fa-hand-peace-o" style="color:#00a65a"></i>  New in town', '<i class="fa fa-line-chart" style="color:#00c0ef"></i> Trending', '<i class="fa fa-star-o" style="color:#f39c12"></i> Top Sellers', '<i class="fa fa-heart-o" style="color:#dd4b39"></i> Most popular',
  ];
  shuffle($array);

for ($i = 0; $i < 4; $i++) {
    ?>
<h2 class="page-header">
  <?php 

echo array_pop($array).'   <a href=\'\'><small>show all</small></a>';
    ?>
</h2>
          <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <a class="info-box bg-aqua" onclick="alert('hi')">
            <img title="Ekdum Jhakkas hai boley to bidu" style="" class="info-box-icon" src="<?php echo get_template_directory_uri();
    ?>/dist/img/user7-128x128.jpg" alt="User Avatar">


            <div class="info-box-content">
              <span class="info-box-text">Chicken Tandoori</span>
              <span class="info-box-number"><i class="fa fa-inr" ></i> 78</span>

              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                  <span class="progress-description">
                    by yummybites
                  </span>
            </div>
            <!-- /.info-box-content -->
          </a>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <a class="info-box bg-green" onclick="alert('hi')">
            <img title="Ekdum Jhakkas hai boley to bidu" style="" class="info-box-icon" src="<?php echo get_template_directory_uri();
    ?>/dist/img/user1-128x128.jpg" alt="User Avatar">

            <div class="info-box-content">
              <span class="info-box-text">Ras Malai</span>
              <span class="info-box-number"><i class="fa fa-inr" ></i> 41</span>

              <div style="display: none;" class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                  <span class="progress-description">by vmdeparmental</span>
            </div>
            <!-- /.info-box-content -->
          </a>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <a class="info-box bg-yellow" onclick="alert('hi')">
            <img title="Ekdum Jhakkas hai boley to bidu" style="" class="info-box-icon" src="<?php echo get_template_directory_uri();
    ?>/dist/img/user3-128x128.jpg" alt="User Avatar">


            <div class="info-box-content">
              <span class="info-box-text">Butter Naan</span>
              <span class="info-box-number"><i class="fa fa-inr" ></i> 63</span>

              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                  <span class="progress-description">
                    by vinayakfoods
                  </span>
            </div>
            <!-- /.info-box-content -->
          </a>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <a class="info-box bg-red" onclick="alert('hi')">
            <img title="Ekdum Jhakkas hai boley to bidu" style="" class="info-box-icon" src="<?php echo get_template_directory_uri();
    ?>/dist/img/user4-128x128.jpg" alt="User Avatar">


            <div class="info-box-content">
              <span class="info-box-text">paneer lababdar</span>
              <span class="info-box-number"><i class="fa fa-inr" ></i> 27</span>

              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                  <span class="progress-description">
                    by pankajgarments
                  </span>
            </div>
            <!-- /.info-box-content -->
          </a>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>

<?php

}
?>

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

<?php get_template_part('template/main_footer'); ?>