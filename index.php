<?php
/*
Plugin Name: Student Management Plugin
Description: A WordPress plugin for managing students.
Version: 1.0
Author: Your Name
*/

// Register a custom post type for Students
function add_dm_students_plugin_menu() {
    // Top-level menu item
    add_menu_page(
        'Students',
        'Students',
        'manage_options',
        'dm_students_plugin',
        'dm_students_page',
        'dashicons-businessman',
        6
    );

    // Submenu item for Admission
    add_submenu_page(
        'dm_students_plugin', // Parent slug
        'Admission', // Page title
        'Admission', // Menu title
        'manage_options',
        'dm_admission_submenu', // Menu slug
        'dm_admission_page' // Callback function for Admission submenu
    );
    
    // Submenu item for Pending Admission
add_submenu_page(
    'dm_students_plugin', // Parent slug
    'Pending Admission', // Page title
    'Pending Admission', // Menu title
    'manage_options',
    'dm_pending_admission_submenu', // Menu slug
    'dm_pending_admission_page' // Callback function for Pending Admission submenu
);
}
add_action('admin_menu', 'add_dm_students_plugin_menu');

// Include necessary files
include_once('admission_form.php');
include_once('student_list.php');
include_once('search.php');
// include_once('student_admission.php');

function dm_pending_admission_page() {
    // Query pending admission posts
    $args = array(
        'post_type' => 'students',
        'meta_key' => 'admission_status',
        'meta_value' => 'pending',
        // Add any other query parameters as needed
    );
    $pending_admissions = new WP_Query($args);

    // Display the list of pending admissions
    if ($pending_admissions->have_posts()) {
        // Loop through and display each pending admission
        while ($pending_admissions->have_posts()) {
            $pending_admissions->the_post();
            // Display student details and approve/delete buttons
            // ...
        }
    } else {
        echo 'No pending admissions found.';
    }
}
// Approve button action
if (isset($_POST['approve_admission'])) {
    $post_id = sanitize_text_field($_POST['admission_id']);
    // Update admission status to 'approved'
    update_post_meta($post_id, 'admission_status', 'approved');
    // Publish the student post
    $post_data = array(
        'ID' => $post_id,
        'post_status' => 'publish'
    );
    wp_update_post($post_data);
    // Redirect or display a success message
}

// Delete button action
if (isset($_POST['delete_admission'])) {
    $post_id = sanitize_text_field($_POST['admission_id']);
    // Delete the student post
    wp_delete_post($post_id, true); // Set to true to force delete
    // Redirect or display a success message
}
