<?php
echo '<div class="wrap">';
echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';
echo '<form action="options.php" method="post">';

settings_fields('menu_shortcode_options');
do_settings_sections('menu_shortcode-generator');
submit_button();

echo '</form>';
echo '</div>';
