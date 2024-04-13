<?php
/*
Plugin Name: Menu Shortcode Generator
Plugin URI: https://github.com/aaditya-cpu/MenuShortcodeGenerator
Description: Allows for the generation of shortcodes for menus and their display via an admin interface. Users can configure menu properties and embed them using shortcodes.
Version: 1.0
Author: Aaditya Uzumaki
Author URI: https://Goenka.dev
*/

// Prevent direct access to the script
defined('ABSPATH') or die('No script kiddies please!');

// Include the core class file
include_once plugin_dir_path(__FILE__) . 'includes/class-menu-shortcode.php';
// require_once(plugin_dir_path(__FILE__) . '../admin/partials/admin-page.php');

/**
 * Registers the hook that is fired when the plugin is activated.
 * Using a static method of the Menu_Shortcode class for activation.
 */
register_activation_hook(__FILE__, array('Menu_Shortcode', 'activate'));

/**
 * Function to handle the initialization of the Menu_Shortcode class.
 */
function run_menu_shortcode() {
    // Check if the class exists before attempting to use it
    if (class_exists('Menu_Shortcode')) {
        try {
            $menu_shortcode = new Menu_Shortcode();
        } catch (Exception $e) {
            error_log('Error initializing Menu Shortcode: ' . $e->getMessage());
            // Optionally, you can handle the error more gracefully and notify the admin or user.
            if (WP_DEBUG === true) {
                wp_die('An error occurred: ' . $e->getMessage());
            }
        }
    } else {
        error_log('Error: Class Menu_Shortcode does not exist.');
        if (WP_DEBUG === true) {
            wp_die('Error: Class Menu_Shortcode does not exist.');
        }
    }
}

// Hook the run function to the WordPress 'plugins_loaded' action to ensure the plugin has access to all WordPress functions.
add_action('plugins_loaded', 'run_menu_shortcode');
