<?php

class YuccaSay_Product_CPT extends AdminPageFramework_PostType
{
    /**
     * Automatically called with the 'wp_loaded' hook.
     */
    public function setUp()
    {
        $my_labels = [
        'name'                  => _x('Products', 'Post Type General Name', 'text_domain'),
        'singular_name'         => _x('Product', 'Post Type Singular Name', 'text_domain'),
        'menu_name'             => __('Product', 'text_domain'),
        'name_admin_bar'        => __('Product', 'text_domain'),
        'archives'              => __('Product Archives', 'text_domain'),
        'parent_item_colon'     => __('Parent Product:', 'text_domain'),
        'all_items'             => __('All Products', 'text_domain'),
        'add_new_item'          => __('Add New Product', 'text_domain'),
        'add_new'               => __('Add New', 'text_domain'),
        'new_item'              => __('New Product', 'text_domain'),
        'edit_item'             => __('Edit Product', 'text_domain'),
        'update_item'           => __('Update Product', 'text_domain'),
        'view_item'             => __('View Product', 'text_domain'),
        'search_items'          => __('Search Product', 'text_domain'),
        'not_found'             => __('Not found', 'text_domain'),
        'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
        'featured_image'        => __('Featured Image', 'text_domain'),
        'set_featured_image'    => __('Set featured image', 'text_domain'),
        'remove_featured_image' => __('Remove featured image', 'text_domain'),
        'use_featured_image'    => __('Use as featured image', 'text_domain'),
        'insert_into_item'      => __('Insert into product', 'text_domain'),
        'uploaded_to_this_item' => __('Uploaded to this product', 'text_domain'),
        'items_list'            => __('Products list', 'text_domain'),
        'items_list_navigation' => __('Products list navigation', 'text_domain'),
        'filter_items_list'     => __('Filter Products list', 'text_domain'),
    ];
        $my_rewrite = [
        'slug'                  => 'product',
        'with_front'            => true,
        'pages'                 => true,
        'feeds'                 => true,
    ];

        $this->setArguments([
        'label'                 => __('Product', 'text_domain'),
        'description'           => __('YuccaShop product POST TYPE', 'text_domain'),
        'labels'                => $my_labels,
        'supports'              => ['title', 'thumbnail'],
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-screenoptions',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'rewrite'               => $my_rewrite,
         'capability_type'      => 'post',
//         //'map_meta_cap' => true,
//          'capabilities' => array(

// // meta caps (don't assign these to roles)
// 'edit_post'              => 'edit_ysy_product',
// 'read_post'              => 'read_ysy_product',
// 'delete_post'            => 'delete_ysy_product',

// // primitive/meta caps
// 'create_posts'           => 'create_ysy_products',

// // primitive caps used outside of map_meta_cap()
// 'edit_posts'             => 'edit_ysy_products',
// 'edit_others_posts'      => 'manage_ysy_products',
// 'publish_posts'          => 'manage_ysy_products',
// 'read_private_posts'     => 'read',

// // primitive caps used inside of map_meta_cap()
// 'read'                   => 'read',
// 'delete_posts'           => 'manage_ysy_products',
// 'delete_private_posts'   => 'manage_ysy_products',
// 'delete_published_posts' => 'manage_ysy_products',
// 'delete_others_posts'    => 'manage_ysy_products',
// 'edit_private_posts'     => 'edit_ysy_products',
// 'edit_published_posts'   => 'edit_ysy_products'
// )
            ]);

        $this->addTaxonomy(
            'product-category',  // taxonomy slug
            [                  // argument - for the argument array keys, refer to : http://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments
                'labels'                => [
        'name'                       => _x('Product Categories', 'Taxonomy General Name', 'text_domain'),
        'singular_name'              => _x('Product Category', 'Taxonomy Singular Name', 'text_domain'),
        'menu_name'                  => __('Product Category', 'text_domain'),
        'all_items'                  => __('All Category', 'text_domain'),
        'parent_item'                => __('Parent Category', 'text_domain'),
        'parent_item_colon'          => __('Parent Category:', 'text_domain'),
        'new_item_name'              => __('New Category Name', 'text_domain'),
        'add_new_item'               => __('Add New Category', 'text_domain'),
        'edit_item'                  => __('Edit Category', 'text_domain'),
        'update_item'                => __('Update Category', 'text_domain'),
        'view_item'                  => __('View Category', 'text_domain'),
        'separate_items_with_commas' => __('Separate categories with commas', 'text_domain'),
        'add_or_remove_items'        => __('Add or remove category', 'text_domain'),
        'choose_from_most_used'      => __('Choose from the most used', 'text_domain'),
        'popular_items'              => __('Popular Category', 'text_domain'),
        'search_items'               => __('Search Category', 'text_domain'),
        'not_found'                  => __('Not Found', 'text_domain'),
        'no_terms'                   => __('No Category', 'text_domain'),
        'items_list'                 => __('Category list', 'text_domain'),
        'items_list_navigation'      => __('Categories list navigation', 'text_domain'),
    ],
                'hierarchical'               => true,
        'public'                             => true,
        'show_ui'                            => true,
        'show_admin_column'                  => true,
        'show_in_nav_menus'                  => true,
        'show_tagcloud'                      => true,
                'show_table_filter'          => true,    // framework specific key
                'show_in_sidebar_menus'      => true,    // framework specific key
            ]
        );
    }
}

new YuccaSay_Product_CPT('yuccasay_product');

//-----------------------------------------------------------------------------------------

class YuccaSay_Order_CPT extends AdminPageFramework_PostType
{
    /**
     * Automatically called with the 'wp_loaded' hook.
     */
    public function setUp()
    {
        $my_labels = [
        'name'                  => _x('Orders', 'Post Type General Name', 'text_domain'),
        'singular_name'         => _x('Order', 'Post Type Singular Name', 'text_domain'),
        'menu_name'             => __('Order', 'text_domain'),
        'name_admin_bar'        => __('Order', 'text_domain'),
        'archives'              => __('Order Archives', 'text_domain'),
        'parent_item_colon'     => __('Parent Order:', 'text_domain'),
        'all_items'             => __('All Orders', 'text_domain'),
        'add_new_item'          => __('Add New Order', 'text_domain'),
        'add_new'               => __('Add New', 'text_domain'),
        'new_item'              => __('New Order', 'text_domain'),
        'edit_item'             => __('Edit Order', 'text_domain'),
        'update_item'           => __('Update Order', 'text_domain'),
        'view_item'             => __('View Order', 'text_domain'),
        'search_items'          => __('Search Order', 'text_domain'),
        'not_found'             => __('Not found', 'text_domain'),
        'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),


        'insert_into_item'      => __('Insert into Order', 'text_domain'),
        'uploaded_to_this_item' => __('Uploaded to this Order', 'text_domain'),
        'items_list'            => __('Orders list', 'text_domain'),
        'items_list_navigation' => __('Orders list navigation', 'text_domain'),
        'filter_items_list'     => __('Filter Products list', 'text_domain'),
    ];
        $my_rewrite = [
        'slug'                  => 'order',
        'with_front'            => true,
        'pages'                 => true,
        'feeds'                 => true,
    ];

        $this->setArguments([
        'label'                 => __('Order', 'text_domain'),
        'description'           => __('YuccaShop Order POST TYPE', 'text_domain'),
        'labels'                => $my_labels,
        //'supports'              => array( 'title', 'thumbnail', ),
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        //'menu_icon'             => 'dashicons-screenoptions',
        'show_in_admin_bar'      => true,
        'show_in_nav_menus'      => true,
        'can_export'             => true,
        'has_archive'            => true,
        'exclude_from_search'    => false,
        'publicly_queryable'     => true,
        'rewrite'                => $my_rewrite,
         'capability_type'       => 'post',
            ]);
    }
}

new YuccaSay_Order_CPT('yuccasay_order');
//----------------------------------------------------------------------------------------------------
class APF_Tutorial_CustomPostTypeMetaBox extends AdminPageFramework_MetaBox
{
    /*
     * Use the setUp() method to define settings of this meta box.
     */
    public function setUp()
    {

        /*
         * Adds setting fields in the meta box.
         */
        $this->addSettingFields(
            [
                'field_id'  => 'product_short_description',
                'type'      => 'text',
                'title'     => __('Short Description', 'admin-page-framework-tutorial'),
            ],
            [
                'field_id'  => 'product_full_description',
                'type'      => 'textarea',
                'title'     => __('Brief Description', 'admin-page-framework-tutorial'),
            ],
            [
                'field_id'  => 'product_price',
                'type'      => 'number',
                'title'     => __('Price', 'admin-page-framework-tutorial'),
            ],
            [
                'field_id'  => 'product_featured_image',
                'type'      => 'image',
                'title'     => __('Product Featured Image', 'admin-page-framework-tutorial'),
            ]
        );
    }
}



new APF_Tutorial_CustomPostTypeMetaBox(
    null,   // meta box ID - can be null.
    __('Tutorial - Add a Meta Box to Custom Post Type', 'admin-page-framework-demo'), // title
    ['yuccasay_product'],                 // post type slugs: post, page, etc.
    'normal',                                      // context
    'high'                                          // priority
);
//----------------------------------------------------------------------------------------------------
class Yuccasay_order_metabox extends AdminPageFramework_MetaBox
{
    /*
     * Use the setUp() method to define settings of this meta box.
     */
    public function setUp()
    {

        /*
         * Adds setting fields in the meta box.
         */
        $this->addSettingFields(
            [
                'field_id'          => 'delivery_address_name',
                'type'              => 'text',
                'title'             => __('name', 'admin-page-framework-tutorial'),
                'attributes'        => [
                   // 'style'          => 'width:100%',
                    'readonly'      => 'readonly',
                    // 'disabled' => 'disabled', // disabled can be specified like so
                ],
            ],
            [
                'field_id'  => 'delivery_address_flat',
                'type'      => 'text',
                'title'     => __('flat no.', 'admin-page-framework-tutorial'),
            ],
            [
                'field_id'  => 'delivery_address_tower',
                'type'      => 'select',
                'title'     => __('society', 'admin-page-framework-tutorial'),
                'help'      => __('the society in which the person lives'),
                'label'     => [
                    0 => _('Paramount Symfony'),
                    1 => _('Ajnara Genx'),
                    2 => _('Mahagun Mascot'),


                    ],
                'tip' => 'hi there tip',
            ],
            [
                'field_id'  => 'delivery_address_mobile',
                'type'      => 'number',
                'title'     => __('mobile no.', 'admin-page-framework-tutorial'),
            ]
        );
    }
}



new Yuccasay_order_metabox(
    null,   // meta box ID - can be null.
    __('delivery address', 'admin-page-framework-demo'), // title
    ['yuccasay_order'],                 // post type slugs: post, page, etc.
    'side',                                      // context
    'low'                                          // priority
);
