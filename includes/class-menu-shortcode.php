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
        require_once(plugin_dir_path(__FILE__) . '../admin/partials/admin-page.php');
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
        $fields = [
            'menu_name' => 'Menu Name',
            'container' => 'Container',
            'container_class' => 'Container Class',
            'container_id' => 'Container ID',
            'menu_class' => 'Menu Class',
            'menu_id' => 'Menu ID',
            'echo' => 'Echo'
        ];
    
        foreach ($fields as $field => $label) {
            $callback = $field === 'echo' ? 'render_checkbox_field' : ($field === 'menu_name' ? 'render_menu_name_dropdown' : 'render_input_text_field');
            add_settings_field(
                $field,
                $label,
                array($this, $callback),
                'menu-shortcode-generator',
                'menu_shortcode_settings',
                array('field' => $field)
            );
        }
    }

    public function render_menu_name_dropdown($args) {
        $options = get_option('menu_shortcode_options');
        $field = $args['field'];
        $menus = wp_get_nav_menus();
        echo '<select name="menu_shortcode_options[' . esc_attr($field) . ']" class="widefat">';
        foreach ($menus as $menu) {
            $selected = isset($options[$field]) && $options[$field] === $menu->slug ? 'selected' : '';
            echo '<option value="' . esc_attr($menu->slug) . '"' . $selected . '>' . esc_html($menu->name) . '</option>';
        }
        echo '</select>';
    }
    // private function add_settings_fields() {
    //     $fields = [
    //         'container_class' => 'Container Class',
    //         'container_id' => 'Container ID',
    //         'menu_class' => 'Menu Class',
    //         'menu_id' => 'Menu ID',
    //         'echo' => 'Echo'
    //     ];

    //     foreach ($fields as $field => $label) {
    //         $callback = $field === 'echo' ? 'render_checkbox_field' : 'render_input_text_field';
    //         add_settings_field(
    //             $field,
    //             $label,
    //             array($this, $callback),
    //             'menu-shortcode-generator',
    //             'menu_shortcode_settings',
    //             array('field' => $field)
    //         );
    //     }
    // }

    public function render_input_text_field($args) {
        $options = get_option('menu_shortcode_options');
        $field = $args['field'];
        echo '<input type="text" name="menu_shortcode_options[' . esc_attr($field) . ']" value="' . esc_attr($options[$field] ?? '') . '" class="widefat">';
    }

    public function render_checkbox_field($args) {
        $options = get_option('menu_shortcode_options');
        $field = $args['field'];
        $checked = isset($options[$field]) && $options[$field] ? 'checked' : '';
        echo '<input type="checkbox" name="menu_shortcode_options[' . esc_attr($field) . ']" ' . $checked . '>';
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

        $menu_args = array_filter($options, function($value) { return !is_null($value) && $value !== ''; });

        $menu_args['echo'] = false; // Ensure this is always set to false to return the menu

        return wp_nav_menu($menu_args);
    }
}
?>
