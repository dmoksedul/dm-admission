<?php
// Edit student form
function display_edit_student_form() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'dm_students';

    // Check if a student ID is provided in the URL
    if (isset($_GET['student_id'])) {
        $student_id = absint($_GET['student_id']);

        // Retrieve the student data from the database
        $student = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $student_id)
        );

        if ($student) {
            // Include the edit student form template
            include('student_edit_form.php');
        } else {
            // Display an error message if the student doesn't exist
            echo '<div class="wrap">';
            echo '<h2>Error: Student not found</h2>';
            echo '<p>The requested student does not exist.</p>';
            echo '</div>';
        }
    } else {
        // Display an error message if no student ID is provided
        echo '<div class="wrap">';
        echo '<h2>Error: Student ID not provided</h2>';
        echo '<p>No student ID was provided in the URL.</p>';
        echo '</div>';
    }
}


// edit form
