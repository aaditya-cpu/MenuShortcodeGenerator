<?php
echo '<div class="wrap">';
echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';
echo '<form action="options.php" method="post">';

settings_fields('menu_shortcode_options'); // This should match the option group in register_setting
do_settings_sections('menu-shortcode-generator'); // This should match the menu slug from add_menu_page

submit_button();

echo '</form>';
echo '</div>';
