<?php
// Create a new database table
global $wpdb;
$table_name = $wpdb->prefix . 'dm_students_result';

$charset_collate = $wpdb->get_charset_collate();

$sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    student_name varchar(100) NOT NULL,
    student_registration_number varchar(50) NOT NULL,
    student_roll_number varchar(50) NOT NULL,
    subject_bangla varchar(10) NOT NULL,
    subject_english varchar(10) NOT NULL,
    subject_ict varchar(10) NOT NULL,
    subject_history varchar(10) NOT NULL,
    subject_math varchar(10) NOT NULL,
    -- Add columns for other subjects here
    PRIMARY KEY (id)
) $charset_collate;";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);


// Function to get student details by registration number
function get_student_details_by_registration_number($registration_number) {
    global $wpdb;

    // Define the table name for the dm_students_esar database
    $table_name = $wpdb->prefix . 'dm_students_esar';

    // Retrieve the student details based on the registration number
    $student_details = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT student_name, student_registration_number, student_roll_number, subject_list
            FROM $table_name
            WHERE student_registration_number = %s",
            $registration_number
        )
    );

    return $student_details;
}

// Function to insert student result into the database
function insert_student_result($student_name, $student_registration_number, $student_roll_number, $subject_marks) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'dm_students_result';

    $data = array(
        'student_name' => $student_name,
        'student_registration_number' => $student_registration_number,
        'student_roll_number' => $student_roll_number,
    );

    // Check and add subject marks if they exist
    if (isset($subject_marks['bangla'])) {
        $data['subject_bangla'] = $subject_marks['bangla'];
    }
    if (isset($subject_marks['english'])) {
        $data['subject_english'] = $subject_marks['english'];
    }
    if (isset($subject_marks['ict'])) {
        $data['subject_ict'] = $subject_marks['ict'];
    }
    if (isset($subject_marks['history'])) {
        $data['subject_history'] = $subject_marks['history'];
    }
    if (isset($subject_marks['math'])) {
        $data['subject_math'] = $subject_marks['math'];
    }
    // Add other subjects here

    $wpdb->insert($table_name, $data);
}



function display_student_results_page() {
    // Content to display on the submenu page
    echo '<div class="wrap">';
    echo '<h2>Student Results</h2>';

    if (isset($_POST['search_student'])) {
        $registration_number = sanitize_text_field($_POST['student_registration_number']);

        // Get student details by registration number
        $student_details = get_student_details_by_registration_number($registration_number);

        if ($student_details) {
            // Display a form with input fields for student details
            echo '<form method="post" action="">';
            echo '<label for="student_name">Student Name:</label>';
            echo '<input type="text" name="student_name" value="' . esc_attr($student_details->student_name) . '" readonly><br>';
            echo '<label for="student_registration_number">Registration Number:</label>';
            echo '<input type="text" name="student_registration_number" value="' . esc_attr($student_details->student_registration_number) . '" readonly><br>';
            echo '<label for="student_roll_number">Roll Number:</label>';
            echo '<input type="text" name="student_roll_number" value="' . esc_attr($student_details->student_roll_number) . '" readonly><br>';

            // Retrieve and split the subject list
            $subject_list = explode(',', $student_details->subject_list);
            foreach ($subject_list as $subject) {
                $subject = trim($subject);
                $subject_lowercase = strtolower($subject); // Convert subject name to lowercase
                echo '<label for="subject_' . $subject_lowercase . '">' . $subject . ':</label>';
                echo '<input type="text" name="subject_' . $subject_lowercase . '" required><br>';
            }

            // Add a submit button
            echo '<input type="submit" name="submit_student_result" value="Submit Result">';
            echo '</form>';
        } else {
            echo '<p>No student found with the provided registration number.</p>';
        }
    } else if (isset($_POST['submit_student_result'])) {
        // Handle form submission and insert data into the dm_students_result database
        $student_name = sanitize_text_field($_POST['student_name']);
        $student_registration_number = sanitize_text_field($_POST['student_registration_number']);
        $student_roll_number = sanitize_text_field($_POST['student_roll_number']);
        
        // Collect subject marks dynamically
        $subject_marks = array();
        foreach ($_POST as $field_name => $field_value) {
            if (strpos($field_name, 'subject_') === 0) {
                $subject = substr($field_name, 8); // Extract subject name from field name
                $subject_marks[$subject] = sanitize_text_field($field_value);
            }
        }

        // Insert the data into the database
        insert_student_result($student_name, $student_registration_number, $student_roll_number, $subject_marks);

        echo '<p>Result successfully added.</p>';
    } else {
        // Display the search form
        echo '<form method="post" action="">';
        echo '<label for="student_registration_number">Search by Registration Number:</label>';
        echo '<input type="text" name="student_registration_number" id="student_registration_number">';
        echo '<input type="submit" name="search_student" value="Search">';
        echo '</form>';
    }

    echo '</div>';
}

function get_student_details_by_registration_and_roll($registration_number, $roll_number) {
    global $wpdb;

    // Define the table name for the dm_students_esar database
    $table_name = $wpdb->prefix . 'dm_students_esar';

    // Retrieve the student details based on the registration number and roll number
    $student_details = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT student_name, student_registration_number, student_roll_number, subject_list
            FROM $table_name
            WHERE student_registration_number = %s AND student_roll_number = %s",
            $registration_number, $roll_number
        )
    );

    return $student_details;
}



// Function to get student results by registration number from dm_students_result
function get_student_results_by_registration_number($registration_number) {
    global $wpdb;

    // Define the table name for the dm_students_result database
    $table_name = $wpdb->prefix . 'dm_students_result';

    // Retrieve the student results based on the registration number
    $student_results = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT subject_bangla, subject_english, subject_ict, subject_history, subject_math
            FROM $table_name
            WHERE student_registration_number = %s",
            $registration_number
        )
    );

    return $student_results;
}



function display_student_results_shortcode() {
    ob_start();
    // Content to display on the page
    echo '<div class="wrap">';
    echo '<h2>Search Student Results</h2>';

    if (isset($_POST['search_student'])) {
        $registration_number = sanitize_text_field($_POST['student_registration_number']);
        $roll_number = sanitize_text_field($_POST['student_roll_number']);

        // Get student details by registration number and roll number from dm_students_esar
        $student_details_esar = get_student_details_by_registration_and_roll($registration_number, $roll_number);

        // Get student results by registration number from dm_students_result
        $student_results = get_student_results_by_registration_number($registration_number);

        if ($student_details_esar && $student_results) {
            echo '<p>Student Name: ' . esc_html($student_details_esar->student_name) . '</p>';
            echo '<p>Registration Number: ' . esc_html($student_details_esar->student_registration_number) . '</p>';
            echo '<p>Roll Number: ' . esc_html($student_details_esar->student_roll_number) . '</p>';

            // Display subject marks
            echo '<h3>Subject Marks:</h3>';
            echo '<ul>';

            foreach ($student_results as $subject_key => $subject_mark) {
                // Skip subjects with blank marks
                if (empty($subject_mark)) {
                    continue;
                }

                // Convert the subject key to a human-readable subject name
                $subject_name = ucfirst(str_replace('_', ' ', $subject_key));
                // Remove "Subject" text
                $subject_name = str_replace('Subject ', '', $subject_name);

                echo '<li>' . $subject_name . ': ' . esc_html($subject_mark) . '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No student found or no results available.</p>';
        }
    } else {
        // Display the search form
        echo '<form method="post" action="">';
        echo '<label for="student_registration_number">Registration Number:</label>';
        echo '<input type="text" name="student_registration_number" id="student_registration_number"><br>';
        echo '<label for="student_roll_number">Roll Number:</label>';
        echo '<input type="text" name="student_roll_number" id="student_roll_number"><br>';
        echo '<input type="submit" name="search_student" value="Search">';
        echo '</form>';
    }

    echo '</div>';
    return ob_get_clean();
}


// Register shortcode to display student results
add_shortcode('dm_student_result', 'display_student_results_shortcode');
