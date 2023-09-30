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
include_once('pending_admission.php');

