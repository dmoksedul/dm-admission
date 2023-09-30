<?php
/*
Plugin Name: Student Management Plugin
Description: A WordPress plugin for managing students.
Version: 1.0
Author: Your Name
*/

// Register a custom post type for Students
function create_dm_students_post_type() {
    $labels = array(
        'name' => 'Students',
        'singular_name' => 'Student',
        'add_new' => 'Add New Student',
        'add_new_item' => 'Add New Student',
        'edit_item' => 'Edit Student',
        'new_item' => 'New Student',
        'view_item' => 'View Student',
        'view_items' => 'View Students',
        'search_items' => 'Search Students',
        'not_found' => 'No students found',
        'not_found_in_trash' => 'No students found in Trash',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'menu_icon' => 'dashicons-businessman',
        'menu_position' => 5,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
    );

    register_post_type('students', $args);
}
add_action('init', 'create_dm_students_post_type');

function hide_dm_students_menu_item() {
    remove_menu_page('edit.php?post_type=students');
}
add_action('admin_menu', 'hide_dm_students_menu_item');

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
}
add_action('admin_menu', 'add_dm_students_plugin_menu');


// student list here
include_once('admission_form.php');
include_once('student_list.php');
include_once('search.php');







