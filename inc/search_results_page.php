<?php
// Create the submenu page
function dm_students_submenu_page() {
    echo '<div class="wrap">';
    echo '<h2>Search Students by ID</h2>';

    // Display the search form
    echo '<form method="post" action="">';
    echo '<label for="search_student_id">Search Student ID:</label>';
    echo '<input type="text" name="search_student_id" id="search_student_id">';
    echo '<input type="submit" name="submit_search" value="Search">';
    echo '</form>';

    // Handle form submission and display search results
    if (isset($_POST['submit_search'])) {
        // Get the entered student ID
        $search_student_id = sanitize_text_field($_POST['search_student_id']);

        // Perform a database query to search for the student
        global $wpdb;
        $table_name = $wpdb->prefix . 'dm_students';
        $result = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT student_id_number, student_first_name, student_last_name, student_phone_number
                FROM $table_name
                WHERE student_id_number = %s",
                $search_student_id
            )
        );

        // Display search results in form input fields
        if ($result) {
            echo '<h2>Search Result:</h2>';
            foreach ($result as $student) {
                echo '<form method="post" action="">';
                echo '<input type="hidden" name="student_id_number" value="' . esc_attr($student->student_id_number) . '">';
                echo '<input type="text" name="student_first_name" placeholder="First Name" value="' . esc_attr($student->student_first_name) . '" required>';
                echo '<input type="text" name="student_last_name" placeholder="Last Name" value="' . esc_attr($student->student_last_name) . '" required>';
                echo '<input type="tel" name="student_phone_number" placeholder="Phone Number" value="' . esc_attr($student->student_phone_number) . '" required>';
                echo '<input type="submit" name="insert_to_esar" value="Insert to dm_students_esar">';
                echo '</form>';
            }
        } else {
            // No results found
            echo '<p>No student found with the entered ID number.</p>';
        }
    }

    echo '</div>';
}

// Handle inserting data into dm_students_esar when the "Insert to dm_students_esar" button is clicked
if (isset($_POST['insert_to_esar'])) {
    // Get the data from the form submission
    $student_id_number = sanitize_text_field($_POST['student_id_number']);
    $student_first_name = sanitize_text_field($_POST['student_first_name']);
    $student_last_name = sanitize_text_field($_POST['student_last_name']);
    $student_phone_number = sanitize_text_field($_POST['student_phone_number']);

    // Perform the database insert into dm_students_esar
    global $wpdb;
    $esar_table_name = $wpdb->prefix . 'dm_students_esar';

    $data = array(
        'student_id_number' => $student_id_number,
        'student_first_name' => $student_first_name,
        'student_last_name' => $student_last_name,
        'student_phone_number' => $student_phone_number,
    );

    $wpdb->insert($esar_table_name, $data);

    echo '<div class="wrap">';
    echo '<p>Data inserted into dm_students_esar successfully.</p>';
    echo '</div>';
}
?>
