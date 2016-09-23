<!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

          <?php get_template_part('template/left_nav_menu_user_panel'); ?>
          

          <!-- /.search form -->

          <!-- Sidebar Menu -->
          
          <!-- <ul class="sidebar-menu">
            <li class="header">HEADER</li> -->
            

            <!-- Optionally, you can add icons to the links -->
            

            <!-- <li class="active"><a href="#"><i class="fa fa-link"></i> <span>Link</span></a></li>
            
            <li><a href="#"><i class="fa fa-link"></i> <span>Another Link</span></a></li>
            

            <li class="treeview">
              <a href="#"><i class="fa fa-link"></i> <span>Multilevel</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="#">Link in level 2</a></li>
                <li><a href="#">Link in level 2</a></li>
              </ul>
            </li>
          </ul> -->
		<?php wp_nav_menu([

        'theme_location' => 'left_aside_menu',
        'container'      => false,
        'menu_class'     => 'sidebar-menu',
        'link_before'    => '<i class="fa fa-link"></i><span>',
        'link_after'     => '</span>',

        ]); ?>
          <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>