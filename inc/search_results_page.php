<?php


// Callback function to display the search student form
function display_search_student_page() {
    global $wpdb;

    $submit_disabled = false; // Initialize submit button state

    // Display the search form
    echo '<div class="wrap">
        <h2>Search Student</h2>
        <form method="post" action="">
            <label for="student_id_number">Student ID Number:</label>
            <input type="text" name="student_id_number" id="student_id_number" required>
            <input type="submit" name="search_student" value="Search">
        </form>';

    // Handle the form submission and display results
    if (isset($_POST['search_student'])) {
        $student_id_number = sanitize_text_field($_POST['student_id_number']);

        // Query to retrieve student data from the original table, including student_last_name
        $table_name = $wpdb->prefix . 'dm_students';
        $query = $wpdb->prepare("SELECT student_id_number, student_first_name, student_last_name, student_registration_number FROM $table_name WHERE student_id_number = %s", $student_id_number);
        $result = $wpdb->get_row($query);

        if ($result) {
            // Display the search result in a form with a submit button
            echo '<form method="post" action="">
                <label for="result_student_id_number">Student ID Number:</label>
                <input type="text" name="result_student_id_number" id="result_student_id_number" value="' . $result->student_id_number . '" readonly>
                <br>
                <label for="result_student_name">Student Name:</label>
                <input type="text" name="result_student_name" id="result_student_name" value="' . $result->student_first_name . ' ' . $result->student_last_name . '" readonly>
                <br>
                <label for="result_student_registration_number">Student Registration Number:</label>
                <input type="text" name="result_student_registration_number" id="result_student_registration_number" value="' . $result->student_registration_number . '" readonly>
                <br>
                <input type="hidden" name="result_student_id" value="' . $result->student_id_number . '">';
            
            // Check if the student ID already exists in the dm_students_esar table
            $new_table_name = $wpdb->prefix . 'dm_students_esar';
            $existing_student = $wpdb->get_var($wpdb->prepare("SELECT student_id FROM $new_table_name WHERE student_id = %s", $student_id_number));

            if ($existing_student) {
                // Display a message if the student ID already exists and disable the submit button
                echo '<p>Student ID ' . $student_id_number . ' already exists in dm_students_esar table.</p>';
                $submit_disabled = true; // Disable the submit button
            } else {
                // Enable the submit button if the student ID does not exist
                $submit_disabled = false;
            }

            // Output the submit button
            echo '<input type="submit" name="insert_result_student" value="Insert Student" ' . ($submit_disabled ? 'disabled' : '') . '></form>';
        } else {
            // Display an error message if no matching student is found
            echo '<p>No student found with the provided ID.</p>';
        }
    }

    // Handle the insert action
    if (isset($_POST['insert_result_student']) && !$submit_disabled) {
        $student_id_number = sanitize_text_field($_POST['result_student_id']);

        // Check if the student ID already exists in the dm_students_esar table
        $new_table_name = $wpdb->prefix . 'dm_students_esar';
        $existing_student = $wpdb->get_var($wpdb->prepare("SELECT student_id FROM $new_table_name WHERE student_id = %s", $student_id_number));

        if ($existing_student) {
            // Display a message if the student ID already exists
            echo '<p>Student ID ' . $student_id_number . ' already exists in dm_students_esar table.</p>';
        } else {
            // Query to retrieve student data from the original table
            $table_name = $wpdb->prefix . 'dm_students';
            $query = $wpdb->prepare("SELECT * FROM $table_name WHERE student_id_number = %s", $student_id_number);
            $result = $wpdb->get_row($query);

            if ($result) {
                // Insert data into the custom table 'dm_students_esar' with the exact student ID number
                $insert_data = array(
                    'student_id' => $result->student_id_number,
                    'student_name' => $result->student_first_name . ' ' . $result->student_last_name,
                    'student_registration_number' => $result->student_registration_number,
                );
                $wpdb->insert($new_table_name, $insert_data);

                echo '<p>Data inserted into dm_students_esar table.</p>';
            } else {
                // Display an error message if no matching student is found
                echo '<p>No student found with the provided ID.</p>';
            }
        }
    }

    echo '</div>';
}
