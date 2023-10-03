<?php

function enqueue_custom_admin_scripts_and_styles() {
    // Enqueue the custom JavaScript for the admin area
    wp_enqueue_script('custom-admin-scripts', plugin_dir_url(__FILE__) . 'js/script.js', array('jquery'), '', true);

    // Enqueue the custom stylesheet for both admin and front-end
    wp_enqueue_style('custom-admin-styles', plugin_dir_url(__FILE__) . '../css/style.min.css');
}
add_action('admin_enqueue_scripts', 'enqueue_custom_admin_scripts_and_styles');
add_action('wp_enqueue_scripts', 'enqueue_custom_admin_scripts_and_styles');




// shortcode page
function shortcodes_page(){
    ?>
    <h1>Shortcodes</h1>
    <ul>
        <li>[dm_admission_form]</li>
        <li>[dm_student_search]</li>
    </ul>
    <?php
}

?>