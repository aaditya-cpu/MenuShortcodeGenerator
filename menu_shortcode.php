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
require_once plugin_dir_path(__FILE__) . 'includes/class-menu-shortcode.php';

/**
 * Registers the hook that is fired when the plugin is activated.
 * Using a static method of the Menu_Shortcode class for activation.
 */
register_activation_hook(__FILE__, array('Menu_Shortcode', 'activate'));

/**
 * Initialize the plugin by creating an instance of the Menu_Shortcode class.
 */
function run_menu_shortcode() {
    new Menu_Shortcode();
}

// Hook the run function to the WordPress 'plugins_loaded' action to ensure the plugin has access to all WordPress functions.
add_action('plugins_loaded', 'run_menu_shortcode');
