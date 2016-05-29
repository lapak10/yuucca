<?php 
add_action('restrict_manage_posts', 'tsm_filter_post_type_by_taxonomy');
function tsm_filter_post_type_by_taxonomy() {
	global $typenow;
	$post_type = 'yucca-kharcha'; // change to your post type
	$taxonomy  = 'yucca_shop_taxonomy'; // change to your taxonomy
	if ($typenow == $post_type) {
		$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => __("Show All {$info_taxonomy->label}"),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => $selected,
			'show_count'      => true,
			'hide_empty'      => true,
		));
	};
}
/**
 * Filter posts by taxonomy in admin
 * @author  Mike Hemberger
 * @link http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/
 */
add_filter('parse_query', 'tsm_convert_id_to_term_in_query');
function tsm_convert_id_to_term_in_query($query) {
	global $pagenow;
	$post_type = 'yucca-kharcha'; // change to your post type
	$taxonomy  = 'yucca_shop_taxonomy'; // change to your taxonomy
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}

class APF_Tutorial_SideMetaBox1 extends AdminPageFramework_MetaBox {
    
    public function start() {
        
        add_action( 'the_content', array( $this, 'replyToAddContent' ) );
        
    }
        /**
         * Called when a post content is displayed.
         * 
         * @callback    filter      the_content
         * @return      string
         */
        public function replyToAddContent( $sContent ) {
 
            if ( ! is_singular() ) {
                return $sContent;
            }
            if ( ! is_main_query() ) {
                return $sContent;
            }
            global $post;
            if ( 'post' !== $post->post_type ) {
                return $sContent;
            }
            
            $_sMetaData = get_post_meta( $post->ID, 'tutorial_normal_metabox_text', true );
            
            return $sContent
                . '<p>Admin Page Framework Tutorial Metabox Field: ' . $_sMetaData . '</p>';
            
        }
    
    /*
     * Use the setUp() method to define settings of this meta box.
     */
    public function setUp() {
    
        /**
         * Adds setting fields in the meta box.
         */
        $this->addSettingFields(
            
            array(
                'field_id'          => 'yucca_shop_dish_price_full',
                'type'              => 'number',
                'help' =>'You know what i mean ;)',
                'title'             => __( 'Price (full plate)', 'admin-page-framework-tutorial' ),
                'tip' => 'Price of your dish for FULL plate in Rupees'
               
            ),
            array(
                'field_id'          => 'yucca_shop_dish_price_half',
                'type'              => 'number',
                'help' =>'You know what i mean ;)',
                'title'             => __( 'Price (half plate)', 'admin-page-framework-tutorial' ),
                'tip' => 'Price of your dish for Half plate in Rupees'
               
            ),
            array(
                'field_id'          => 'yucca_shop_dish_image',
                'type'              => 'image',
                'help' =>'You know what i mean ;)',
                'title'             => __( 'Picture', 'admin-page-framework-tutorial' ),
                'show_preview' => true,
                'tip' => 'Add a nice picture for your dish.'
            ),
            array(
                'field_id'          => 'yucca_shop_dish_description',
                'type'              => 'textarea',
                'help' =>'You know what i mean ;)',
                'title'             => __( 'Description', 'admin-page-framework-tutorial' ),
                'tip' => 'Write here some basic details about your dish'
                
            )
        );     
    
    }
   
}
 
new APF_Tutorial_SideMetaBox1(
    null,   // meta box ID - can be null.
    __( 'Dish Details', 'admin-page-framework-demo' ), // title
    array( 'yucca-kharcha' ),                               // post type slugs: post, page, etc.
    'normal',                                        // context
    'high'                                          // priority
);
if(!get_role('aunty'))
add_role( 'aunty', __(
 
'Aunty Ji' ),
 
array(
 
'read' => true, // true allows this capability
'edit_posts' => true, // Allows user to edit their own posts
'edit_pages' => true, // Allows user to edit pages
'edit_others_posts' => true, // Allows user to edit others posts not just their own
'create_posts' => true, // Allows user to create new posts
'manage_categories' => false,
'edit_yucca-kharcha' => true, // Allows user to edit their own posts


'create_yucca-kharcha' => true, // Allows user to create new posts
'manage_yucca_shop_taxonomy' => false,// Allows user to manage post categories
'publish_yucca-kharcha' => false, // Allows the user to publish, otherwise posts stays in draft mode
'edit_themes' => false, // false denies this capability. User can’t edit your theme
'install_plugins' => false, // User cant add new plugins
'update_plugin' => false, // User can’t update any plugins
'update_core' => false // user cant perform core updates
 
)
 
);