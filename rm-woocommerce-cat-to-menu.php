<?php
/*
 * Plugin Name: RM Woocommerce Category To Menu
 * Plugin_URI: https://github.com/richard-ma/rm-woocommerce-cat-to-menu
 * Author: Richard Ma
 * Author URI: http://richardma.info
 * Version: 1.0
 * Lincense: MIT
 *
 * Plugin Prefix: rm_ / RM_
 */
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
// check woocommerce
if (in_array( 'woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    // add submenu export to woocommerce
    function rm_add_to_admin_submenu() {
        add_submenu_page(
            'woocommerce',
            __('RM Woocommerce Category To Menu'),
            __('Add Category To Menu'),
            'manage_options',
            'rm_category_to_menu',
            'rm_category_to_menu_callback'
        );
    }
    add_action('admin_menu', 'rm_add_to_admin_submenu');

    // really action
    function rm_category_to_menu_callback() {

        // find all categories of product
        $product_catgories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => 0,
        ));
        //foreach ($product_catgories as $c) {
            //var_dump($c->name);
        //}
        
        // get primary menu id
        $primary_menu_id = False;
        $menus = wp_get_nav_menus();
        if (count($menus) > 0) {
            foreach ($menus as $menu) {
                if ($menu->name == "Primary Menu") {
                    $primary_menu_id = $menu->term_id;
                }
            }
        }

        // delete all menu items of primary menu
        if ($primary_menu_id != false) {
            $menu_items = wp_get_nav_menu_items($primary_menu_id);
            foreach ($menu_items as $menu_item) {
                wp_delete_post($menu_item->ID, true);
            }
        }

        if ($primary_menu_id) {
            wp_update_nav_menu_item($primary_menu_id, 0, array(
                'menu-item-title' =>  __('Home'),
                //'menu-item-classes' => 'home',
                'menu-item-url' => home_url( '/' ), 
                'menu-item-status' => 'publish'));
        }
    }
}
