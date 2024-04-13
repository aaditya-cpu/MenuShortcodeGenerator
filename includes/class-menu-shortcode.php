<?php
class Menu_Shortcode {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'settings_init'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_shortcode('display_menu', array($this, 'render_menu_by_shortcode'));
    }

    public function enqueue_scripts() {
        wp_enqueue_style('menu_shortcode_admin_style', plugin_dir_url(__FILE__) . 'admin/css/admin-style.css');
        wp_enqueue_script('menu_shortcode_admin_script', plugin_dir_url(__FILE__) . 'admin/js/admin-scripts.js', array('jquery'), true);
    }

    public function add_admin_menu() {
        add_menu_page(
            'Menu Shortcode Generator',
            'Menu Shortcode',
            'manage_options',
            'menu-shortcode-generator',
            array($this, 'menu_shortcode_admin_page'),
            'dashicons-menu'
        );
    }

    public function menu_shortcode_admin_page() {
        require_once(plugin_dir_path(__FILE__) . 'admin/partials/admin-page.php');
    }

    public function settings_init() {
        register_setting('menu_shortcode_options', 'menu_shortcode_options', array($this, 'menu_shortcode_options_validate'));

        add_settings_section(
            'menu_shortcode_settings',
            'Shortcode Settings',
            array($this, 'settings_section_callback'),
            'menu-shortcode-generator'
        );

        $this->add_settings_fields();
    }

    private function add_settings_fields() {
        add_settings_field('container_class', 'Container Class', array($this, 'render_input_text_field'), 'menu-shortcode-generator', 'menu_shortcode_settings', array('field' => 'container_class'));
        add_settings_field('container_id', 'Container ID', array($this, 'render_input_text_field'), 'menu-shortcode-generator', 'menu_shortcode_settings', array('field' => 'container_id'));
        add_settings_field('menu_class', 'Menu Class', array($this, 'render_input_text_field'), 'menu-shortcode-generator', 'menu_shortcode_settings', array('field' => 'menu_class'));
        add_settings_field('menu_id', 'Menu ID', array($this, 'render_input_text_field'), 'menu-shortcode-generator', 'menu_shortcode_settings', array('field' => 'menu_id'));
        add_settings_field('echo', 'Echo', array($this, 'render_checkbox_field'), 'menu-shortcode-generator', 'menu_shortcode_settings', array('field' => 'echo'));
    }

    public function render_input_text_field($args) {
        $options = get_option('menu_shortcode_options');
        $field = $args['field'];
        echo '<input type="text" name="menu_shortcode_options[' . $field . ']" value="' . esc_attr($options[$field] ?? '') . '" class="widefat">';
    }

    public function render_checkbox_field($args) {
        $options = get_option('menu_shortcode_options');
        $field = $args['field'];
        $checked = isset($options[$field]) && $options[$field] ? 'checked' : '';
        echo '<input type="checkbox" name="menu_shortcode_options[' . $field . ']" ' . $checked . '>';
    }

    public function menu_shortcode_options_validate($input) {
        $new_input = [];
        foreach ($input as $key => $value) {
            $new_input[$key] = sanitize_text_field($value);
        }
        return $new_input;
    }

    public function settings_section_callback() {
        echo 'Configure the settings for your menu shortcode.';
    }

    public function render_menu_by_shortcode($atts) {
        $options = shortcode_atts(array(
            'name' => '',
            'container' => 'div',
            'container_class' => '',
            'container_id' => '',
            'menu_class' => 'menu',
            'menu_id' => '',
            'echo' => false,
        ), $atts);

        return wp_nav_menu(array(
            'menu' => $options['name'],
            'container' => $options['container'],
            'container_class' => $options['container_class'],
            'container_id' => $options['container_id'],
            'menu_class' => $options['menu_class'],
            'menu_id' => $options['menu_id'],
            'echo' => $options['echo']
        ));
    }
}
