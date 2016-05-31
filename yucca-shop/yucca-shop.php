<?php

/**
 * The plugin bootstrap file.
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              about.me/anand.kmk
 * @since             1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Yucca Shop
 * Plugin URI:        yucca-shop
 * Description:       It actually does nothing. Just some rock n roll and thats how we all do it.
 * Version:           1.0.0
 * Author:            Anand Kumar
 * Author URI:        about.me/anand.kmk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       yucca-shop
 * Domain Path:       /languages
 * Plugin Type: Piklist
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}




include dirname(__FILE__).'/library_apf/admin-page-framework.php';
include dirname(__FILE__).'/custom_taxonomy_filter.php';
include dirname(__FILE__).'/create_yuccasay_user_roles.php';
include dirname(__FILE__).'/create_post_type_products.php';

class APF_CreatePage extends AdminPageFramework
{
    /**
     * The set-up method which is triggered automatically with the 'wp_loaded' hook.
     *
     * Here we define the setup() method to set how many pages, page titles and icons etc.
     */
    public function setUp()
    {

        // Create the root menu - specifies to which parent menu to add.
        $this->setRootMenuPage('Settings');

        // Add the sub menus and the pages.
        $this->addSubMenuItems(
            [
                'title'     => 'Theme Options',  // page and menu title
                'page_slug' => 'my_first_settings_page',     // page slug
            ]
        );
    }

    public function load_my_first_settings_page($oAdminPage)
    {

        /*
         * Adds setting fields in the meta box.
         */
        $this->addSettingFields(

            [
                'field_id'          => 'yucca_shop_title_full_dark',
                'type'              => 'text',

                'title'             => __('Site Title for dark (full)', 'admin-page-framework-tutorial'),
                'tip'               => 'Name of site in full width for dark part',

            ], [
                'field_id'          => 'yucca_shop_title_full_light',
                'type'              => 'text',

                'title'             => __('Site Title for light (full)', 'admin-page-framework-tutorial'),
                'tip'               => 'Name of site in full width for light part',

            ],
            [
                'field_id'          => 'yucca_shop_title_half_dark',
                'type'              => 'text',

                'title'             => __('Site Title dark (half)', 'admin-page-framework-tutorial'),
                'tip'               => 'Name of site in small screen for dark part',

            ],
            [
                'field_id'          => 'yucca_shop_title_half_light',
                'type'              => 'text',

                'title'             => __('Site Title light (full)', 'admin-page-framework-tutorial'),
                'tip'               => 'Name of site in small width for lighter part',

            ],
            [
                'field_id'          => 'yucca_shop_main_title',
                'type'              => 'textarea',

                'title'             => __('Main title', 'admin-page-framework-tutorial'),
                'tip'               => 'Title of the website which comes in the top bar.',

            ],
             [
                'field_id'          => 'submit_in_page_meta_box',
                'type'              => 'submit',
                'show_title_column' => false,
                'label_min_width'   => '',
                'attributes'        => [
                    'field' => [
                        'style' => 'float:left; width:auto;',
                    ],
                ],
            ]
        );
    }
}
new APF_CreatePage();

include dirname(__FILE__).'/theme_setting.php';
