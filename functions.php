<?php
/*
Plugin Name: DM Admission
Description: A plugin to display an admission form and save data to the database.
Version: 1.0
Author: Moksedul Islam
Author URI: https://moksedul.dev/
Text Domain: dmoksedul
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
        subject_list VARCHAR(255) NOT NULL,
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
        student_session VARCHAR(255) NOT NULL, /* New field for student session */
        student_id_number VARCHAR(255) NOT NULL, /* New field for student ID number */
        student_registration_number VARCHAR(255) NOT NULL, /* New field for student registration number */
        student_roll_number VARCHAR(255) NOT NULL, /* New field for student roll number */
        PRIMARY KEY (id)
    ) $charset_collate;";

    // Execute the SQL query to create the table
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'admission_form_plugin_activation');



function create_custom_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'dm_students_esar';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        student_name VARCHAR(255) NOT NULL,
        student_id_number INT NOT NULL,
        student_registration_number VARCHAR(255) NOT NULL,
        student_roll_number INT NOT NULL,
        student_phone_number VARCHAR(20) NOT NULL,
        subject_list TEXT NOT NULL,
        class VARCHAR(255) NOT NULL,
        exam VARCHAR(255) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'create_custom_table');



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
        'dm_admission',        // Menu slug (unique identifier)
        'display_student_list',   // Callback function to display the page content
        'dashicons-book-alt',       // Icon for the menu (you can choose from dashicons)
        15,
    );

    // Add a submenu page for the student list
    add_submenu_page(
        'dm_admission',       // Parent menu slug
        'All Students',         // Page title
        'All Students',         // Menu title
        'manage_options',       // Capability required to access the menu
        'dm_admission',         // Menu slug (unique identifier)
        'display_student_list'  // Callback function to display the student
    );
    
     // Add a submenu page for pending admissions
     add_submenu_page(
        'dm_admission',             // Parent menu slug (should match the top-level menu slug)
        'Pending Admission',          // Page title
        'Pending Admission',          // Menu title
        'manage_options',             // Capability required to access the menu
        'pending-admission',          // Menu slug (unique identifier)
        'display_pending_admission'   // Callback function to display the pending admission content
    );
    add_submenu_page(
        'your-main-menu-slug', // Parent menu slug
        'Edit Student',         // Page title
        'Edit Student',         // Menu title
        'manage_options',       // Capability required to access the page
        'edit-student',         // Page slug
        'display_edit_student_form' // Callback function to display the page
    );
    // Add a submenu page for admission_form_page
    add_submenu_page(
        'dm_admission',
        'Admission Form',
        'Admission Form',
        'manage_options',
        'admission-form', // Sub-menu page slug
        'admission_form_page' // Callback function for admission_form_page
    );
    // Add a submenu page for importing students from CSV
    add_submenu_page(
        'dm_admission',                        // Parent menu slug
        'Import Students',              // Page title
        'Import Students',              // Menu title
        'manage_options',                        // Capability required to access the menu
        'import-students',                       // Menu slug (unique identifier)
        'import_students_form'                  // Callback function to display the import form
    );
    add_submenu_page(
        'dm_admission', // Parent menu slug (Students)
        'Exam Registration', // Page title
        'Exam Registration', // Menu title
        'manage_options', // Capability required to access
        'exam-submenu-page', // Menu slug
        'exam_submenu_page_content' // Callback function to display content
    );
    // Add the submenu page under the "Exam" menu
    add_submenu_page(
        'dm_admission', // Parent menu slug (In this example, it's a custom post type "exam")
        'Exam Students',           // Page title
        'Exam Students',           // Menu title
        'manage_options',          // Capability required to access the page
        'exam_students',           // Unique slug for the submenu page
        'exam_students_submenu_page_content' // Callback function to display the content
    );
    // Add a submenu page for importing students from CSV
    add_submenu_page(
        'dm_admission',                        // Parent menu slug
        'Shortcodes',              // Page title
        'Shortcodes',              // Menu title
        'manage_options',                        // Capability required to access the menu
        'shortcodes',                       // Menu slug (unique identifier)
        'shortcodes_page'                  // Callback function to display the import form
    );
    // Add a submenu page for the trash
    add_submenu_page(
        'dm_admission',             // Parent menu slug (should match the top-level menu slug)
        'Trash',                    // Page title
        'Trash',                    // Menu title
        'manage_options',           // Capability required to access the menu
        'trash-page',               // Menu slug (unique identifier)
        'display_trash_page'       // Callback function to display the trash page content
    );


}
add_action('admin_menu', 'admission_form_plugin_menu');
// default settings linking
include_once('inc/default.php');

// student admission page linking
include_once('inc/admission_form_admin.php');

// student list page linking
include_once('inc/student_list.php');

// student list export page linking
include_once('inc/export_student.php');

// student list import page linking
include_once('inc/import_student.php');

// student edit page linking
include_once('inc/edit_student.php');

// student edit page linking
include_once('inc/search.php');

// student edit page linking
include_once('inc/id_card.php');

// student edit page linking
include_once('inc/student_admission.php');

// pending student  page linking
include_once('inc/pending_admission.php');

// Include the search_results_page.php file
include_once('inc/admit_card.php');

// ... other includes and functions ...










// Handle the "Restore" action if the URL parameter is set
if (isset($_GET['action']) && $_GET['action'] === 'restore' && isset($_GET['student_id'])) {
    $student_id_to_restore = absint($_GET['student_id']);
    // Update the trashed column for the specific student record to unmark as trashed (set to 0)
    $wpdb->update(
        $table_name,
        array('trashed' => 0), // Set trashed to 0 to unmark as trashed
        array('id' => $student_id_to_restore),
        array('%d'), // Format for trashed column
        array('%d')  // Format for id column
    );
}


function display_trash_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'dm_students';

    // Get the current page number from URL
    $current_page = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
    $per_page = 10; // Number of items per page

    // Calculate the offset for the SQL query
    $offset = ($current_page - 1) * $per_page;

    // Modify your SQL query to fetch trashed students
    $students = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name 
            WHERE trashed = 1  /* Fetch trashed records */
            ORDER BY id DESC LIMIT %d, %d",
            $offset,
            $per_page
        )
    );

    // Calculate the total number of trashed students
    $total_students = $wpdb->get_var(
        "SELECT COUNT(*) FROM $table_name WHERE trashed = 1"
    );

    // Calculate the total number of pages based on the total trashed students and items per page
    $total_pages = ceil($total_students / $per_page);

    // Display the trashed student list table
    echo '<div class="wrap">';
    echo '<h2>Trashed Students</h2>';
    echo '<table id="student_list_table_box" class="wp-list-table widefat fixed">';
    echo '<thead><tr>';
    echo '<th style="width:50px">No</th>';
    echo '<th style="width:90px">Image</th>';
    echo '<th>Name</th>';
    echo '<th>Student ID</th>'; // Add the column for Student ID Number
    echo '<th>Birthday</th>';
    echo '<th>Phone Number</th>';
    echo '<th>Parent Name</th>';
    echo '<th>Location</th>';
    echo '<th>Actions</th>';
    echo '</tr></thead>';
    echo '<tbody>';

    $list_number = ($current_page - 1) * $per_page + 1; // Initialize list number

    foreach ($students as $student) {
        // Get the student's first and last name
        $student_name = $student->student_first_name . ' ' . $student->student_last_name;

        // Get the student's location (city and state)
        $location = $student->student_city . ', ' . $student->student_state;

        echo '<tr>';
        echo '<td>' . esc_html($list_number) . '</td>';
        echo '<td><img src="' . esc_url(wp_get_attachment_image_url($student->student_image, 'thumbnail')) . '" alt="' . esc_attr($student_name) . '" width="50"></td>';
        echo '<td>' . esc_html($student_name) . '</td>';
        echo '<td>' . esc_html($student->student_id_number) . '</td>'; // Display the Student ID Number
        echo '<td>' . esc_html(date('F j, Y', strtotime($student->student_birthdate))) . '</td>';
        echo '<td>' . esc_html($student->student_phone_number) . '</td>';
        echo '<td>' . esc_html($student->student_parent_name) . '</td>';
        echo '<td>' . esc_html($location) . '</td>';
        echo '<td>';
        echo '<a href="?page=edit-student&student_id=' . $student->id . '" class="button">Edit</a>';
        echo ' <a href="?page=dm_admission&action=restore&student_id=' . $student->id . '" class="button">Restore</a>'; // Add Restore button
        echo '</td>';

        echo '</tr>';

        $list_number++; // Increment list number
    }

    echo '</tbody>';
    echo '</table>';

    // Pagination
    echo '<div class="tablenav">';
    echo '<div id="pagination_box" class="tablenav-pages">';
    echo paginate_links(array(
        'base' => add_query_arg('paged', '%#%'),
        'format' => '',
        'prev_text' => '&laquo;',
        'next_text' => '&raquo;',
        'total' => $total_pages,
        'current' => $current_page,
    ));
    echo '</div>';
    echo '</div>';

    echo '</div>';
}
