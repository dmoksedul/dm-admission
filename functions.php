<?php
/*
Plugin Name: DM Admission
Description: A plugin to display an admission form and save data to the database.
Version: 1.0
Author: Moksedul Islam
Author URI: https://moksedul.dev/
*/

// Activation Hook
function admission_form_plugin_activation() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'dm_students';

    // SQL statement to create the table
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        institute_name VARCHAR(255) NOT NULL,
        class VARCHAR(255) NOT NULL,
        section VARCHAR(255) NOT NULL,
        admission_date DATE NOT NULL,
        category VARCHAR(255) NOT NULL,
        student_first_name VARCHAR(255) NOT NULL,
        student_last_name VARCHAR(255) NOT NULL,
        student_gender VARCHAR(20) NOT NULL,
        student_birthdate DATE NOT NULL,
        student_blood_group VARCHAR(10) DEFAULT '', /* New field for blood group */
        student_phone_number VARCHAR(20) NOT NULL,
        student_email VARCHAR(255) NOT NULL,
        student_religion VARCHAR(255) NOT NULL,
        student_nid VARCHAR(255) NOT NULL,
        student_present_address TEXT NOT NULL,
        student_permanent_address TEXT NOT NULL,
        student_city VARCHAR(255) NOT NULL,
        student_state VARCHAR(255) NOT NULL,
        student_previous_institute_name VARCHAR(255) NOT NULL,
        student_previous_institute_qualification VARCHAR(255) NOT NULL,
        student_previous_institute_remarks TEXT NOT NULL,
        student_parent_name VARCHAR(255) NOT NULL,
        student_parent_relation VARCHAR(255) NOT NULL,
        student_father_name VARCHAR(255) NOT NULL,
        student_mother_name VARCHAR(255) NOT NULL,
        student_parent_occupation VARCHAR(255) NOT NULL,
        student_parent_income VARCHAR(255) NOT NULL,
        student_parent_education VARCHAR(255) NOT NULL,
        student_parent_email VARCHAR(255) NOT NULL,
        student_parent_number VARCHAR(20) NOT NULL,
        student_parent_address TEXT NOT NULL,
        student_parent_city VARCHAR(255) NOT NULL,
        student_parent_state VARCHAR(255) NOT NULL,
        student_image INT, /* Changed to INT to store attachment IDs */
        student_parent_image INT, /* Changed to INT to store attachment IDs */
        student_documents INT, /* Changed to INT to store attachment IDs */
        PRIMARY KEY (id)
    ) $charset_collate;";

    // Execute the SQL query to create the table
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'admission_form_plugin_activation');

// Deactivation Hook
function admission_form_plugin_deactivation() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'dm_students';

    // SQL statement to delete the table
    $sql = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query($sql);
}

// Add a top-level menu page for your plugin
function admission_form_plugin_menu() {
    add_menu_page(
        'DM Admission', // Page title
        'DM Admission',        // Menu title
        'manage_options',        // Capability required to access the menu
        'admission-form',        // Menu slug (unique identifier)
        'admission_form_page',   // Callback function to display the page content
        'dashicons-id-alt'       // Icon for the menu (you can choose from dashicons)
    );

    // Add a submenu page for the student list
    add_submenu_page(
        'admission-form',       // Parent menu slug
        'Student List',         // Page title
        'Student List',         // Menu title
        'manage_options',       // Capability required to access the menu
        'student-list',         // Menu slug (unique identifier)
        'display_student_list'  // Callback function to display the student list
    );

    // Add a submenu page for editing students
    add_submenu_page(
        'admission-form',
        'Edit Student',
        'Edit Student',
        'manage_options',
        'edit-student', // Sub-menu page slug
        'edit_student_page' // Callback function for editing students
    );

    // Add a submenu page for importing students from CSV
    add_submenu_page(
        'admission-form',                        // Parent menu slug
        'Import Students from CSV',              // Page title
        'Import Students from CSV',              // Menu title
        'manage_options',                        // Capability required to access the menu
        'import-students',                       // Menu slug (unique identifier)
        'import_students_form'                  // Callback function to display the import form
    );
     // Add a submenu page for pending admissions
     add_submenu_page(
        'admission-form',             // Parent menu slug (should match the top-level menu slug)
        'Pending Admission',          // Page title
        'Pending Admission',          // Menu title
        'manage_options',             // Capability required to access the menu
        'pending-admission',          // Menu slug (unique identifier)
        'display_pending_admission'   // Callback function to display the pending admission content
    );
}
add_action('admin_menu', 'admission_form_plugin_menu');

// student admission page linking
include_once('inc/admission_form_page.php');

// student list page linking
include_once('inc/student_list.php');

// student list export page linking
include_once('inc/student_list_export.php');

// student list import page linking
include_once('inc/import_student_csv.php');

// student edit page linking
include_once('inc/edit_student.php');

// student edit page linking
include_once('inc/search.php');

// student edit page linking
include_once('inc/student_admission.php');
