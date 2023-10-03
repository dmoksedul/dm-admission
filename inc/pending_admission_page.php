<?php
// Create the Pending Admission Page
function pending_admission_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'dm_students'; // Replace with your table name for pending admissions
    $approved_table_name = $wpdb->prefix . 'approved_students'; // Replace with your table name for approved admissions

    if (isset($_POST['approve_admission'])) {
        $admission_id = $_POST['admission_id'];

        // Retrieve the pending admission data
        $pending_admission_data = $wpdb->get_row("SELECT * FROM $table_name WHERE id = $admission_id");

        if ($pending_admission_data) {
            // Insert the data into the approved admissions table
            $wpdb->insert($approved_table_name, $pending_admission_data);

            // Remove the admission entry from the pending list
            $wpdb->delete($table_name, array('id' => $admission_id));

            // Optionally, display a success message
            echo '<div class="updated"><p>Admission Approved Successfully!</p></div>';
        }
    }

    // Display the list of pending admissions
    $pending_admissions = $wpdb->get_results("SELECT * FROM $table_name");

    if ($pending_admissions) {
        echo '<div class="wrap">';
        echo '<h2>Pending Admissions</h2>';
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr><th>Student Name</th><th>Actions</th></tr></thead>';
        echo '<tbody>';

        foreach ($pending_admissions as $admission) {
            echo '<tr>';
            echo '<td>' . $admission->student_first_name . ' ' . $admission->student_last_name . '</td>';
            echo '<td>';
            echo '<form method="post">';
            echo '<input type="hidden" name="admission_id" value="' . $admission->id . '">';
            echo '<input type="submit" name="approve_admission" value="Approve" class="button button-primary">';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo '<p>No pending admissions to display.</p>';
    }
}
