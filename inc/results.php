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
    subject_bangla_1st varchar(10) NOT NULL,
    subject_bangla_2nd varchar(10) NOT NULL,
    subject_english_1st varchar(10) NOT NULL,
    subject_english_2nd varchar(10) NOT NULL,
    subject_math varchar(10) NOT NULL,
    subject_religion varchar(10) NOT NULL,
    subject_ict varchar(10) NOT NULL,
    subject_physics varchar(10) NOT NULL,
    subject_chemistry varchar(10) NOT NULL,
    subject_biology varchar(10) NOT NULL,
    subject_higher_math varchar(10) NOT NULL,
    subject_general_science varchar(10) NOT NULL,
    subject_bangladesh_and_global_studies varchar(10) NOT NULL,
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
    if (isset($subject_marks['bangla_1st'])) {
        $data['subject_bangla_1st'] = $subject_marks['bangla_1st'];
    }
    if (isset($subject_marks['bangla_2nd'])) {
        $data['subject_bangla_2nd'] = $subject_marks['bangla_2nd'];
    }
    if (isset($subject_marks['english_1st'])) {
        $data['subject_english_1st'] = $subject_marks['english_1st'];
    }
    if (isset($subject_marks['english_2nd'])) {
        $data['subject_english_2nd'] = $subject_marks['english_2nd'];
    }
    if (isset($subject_marks['math'])) {
        $data['subject_math'] = $subject_marks['math'];
    }
    if (isset($subject_marks['religion'])) {
        $data['subject_religion'] = $subject_marks['religion'];
    }
    if (isset($subject_marks['ict'])) {
        $data['subject_ict'] = $subject_marks['ict'];
    }
    if (isset($subject_marks['physics'])) {
        $data['subject_physics'] = $subject_marks['physics'];
    }
    if (isset($subject_marks['chemistry'])) {
        $data['subject_chemistry'] = $subject_marks['chemistry'];
    }
    if (isset($subject_marks['biology'])) {
        $data['subject_biology'] = $subject_marks['biology'];
    }
    if (isset($subject_marks['higher_math'])) {
        $data['subject_higher_math'] = $subject_marks['higher_math'];
    }
    if (isset($subject_marks['general_science'])) {
        $data['subject_general_science'] = $subject_marks['general_science'];
    }
    if (isset($subject_marks['bangladesh_and_global_studies'])) {
        $data['subject_bangladesh_and_global_studies'] = $subject_marks['bangladesh_and_global_studies'];
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
                // Replace spaces with underscores in the input field names
                $subject_field_name = 'subject_' . str_replace(' ', '_', $subject_lowercase);
                echo '<label for="' . $subject_field_name . '">' . $subject . ':</label>';
                echo '<input type="text" name="' . $subject_field_name . '" required><br>';
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
            "SELECT subject_bangla_1st, subject_bangla_2nd, subject_english_1st, subject_english_2nd, subject_math, subject_religion, subject_ict, subject_physics, subject_chemistry, subject_biology, subject_higher_math, subject_general_science, subject_bangladesh_and_global_studies
            FROM $table_name
            WHERE student_registration_number = %s",
            $registration_number
        )
    );

    return $student_results;
}


function calculate_grade($marks) {
    if ($marks >= 80) {
        return 'A+';
    } elseif ($marks >= 70) {
        return 'A';
    } elseif ($marks >= 60) {
        return 'A-';
    } elseif ($marks >= 50) {
        return 'B';
    } elseif ($marks >= 40) {
        return 'C';
    }elseif ($marks >= 33) {
        return 'D';
    }
     else {
        return 'F';
    }
}

// Function to calculate GPA points based on grade
function calculate_gpa_points($grade) {
    // Define your GPA points based on your grading scale
    $gpa_scale = array(
        'A+' => 5.0,
        'A' => 4.0,
        'A-' => 3.5,
        'B' => 3.0,
        'C' => 2.0,
        'D' => 1.0,
        'F' => 0.0, // Adjust this value for a failing grade
    );

    // Return the GPA points based on the grade, or 0 for grades not in the scale
    return isset($gpa_scale[$grade]) ? $gpa_scale[$grade] : 0;
}

function display_student_results_shortcode() {
    ob_start();
    // Content to display on the page
    echo '<div class="wrap" id="dm_result_sheed_box">';

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

            echo '<h3>Subject Results:</h3>';
            echo '<table collapse style="border-collapse: collapse; width: 100%;">';
            echo '<thead><tr><th>Serial Number</th><th>Subject Code</th><th>Subject Name</th><th>Grade</th></tr></thead>';
            echo '<tbody>';

            $hasFailed = false; // Flag to track if any subject has failed

            $overall_gpa_points = 0;
            $num_subjects = 0;

            $serial_number = 1;

            foreach ($student_results as $subject_key => $subject_mark) {
                // Skip subjects with blank marks
                if (empty($subject_mark)) {
                    continue;
                }

                // Calculate the grade based on marks
                $grade = calculate_grade($subject_mark);

                // If the grade is "F," set the flag to true
                if ($grade == 'F') {
                    $hasFailed = true;
                }

                // Calculate GPA points based on the grade
                $gpa_points = calculate_gpa_points($grade);

                // Sum GPA points for all subjects
                $overall_gpa_points += $gpa_points;
                $num_subjects++;

                // Convert the subject key to a human-readable subject name
                $subject_name = ucfirst(str_replace('_', ' ', $subject_key));
                // Remove "Subject" text
                $subject_name = str_replace('Subject ', '', $subject_name);

                // Display subject details in a table row, including serial number
                echo '<tr>';
                echo '<td>' . $serial_number . '</td>';
                echo '<td>' . $subject_key . '</td>'; // Subject Code
                echo '<td>' . $subject_name . '</td>';
                echo '<td>' . $grade . '</td>';
                echo '</tr>';

                $serial_number++;
            }

            // Calculate the overall GPA by taking the average of GPA points
            $overall_gpa = ($num_subjects > 0) ? ($overall_gpa_points / $num_subjects) : 0;

            // Determine if the student passed or failed based on the flag
            $passed = (!$hasFailed); // Student passes if no subject has an "F" grade

            echo '</tbody>';
            echo '</table>';

            // Display overall results, showing "Failed" if any subject has failed
            echo '<h3>Overall Results:</h3>';
            echo '<p>Overall GPA: ' . number_format($overall_gpa, 2) . '</p>';
            echo '<p>Status: ' . ($passed ? 'Passed' : 'Failed') . '</p>';
        } else {
            echo '<p>No student found or no results available.</p>';
        }
    } else {
        // Display the search form
        echo '<div id="dm_result_search_box">';
        echo '<form method="post" action="">';
        echo '<label for="student_registration_number">Registration Number:</label>';
        echo '<input type="text" name="student_registration_number" id="student_registration_number" required><br>';
        echo '<label for="student_roll_number">Roll Number:</label>';
        echo '<input type="text" name="student_roll_number" id="student_roll_number" required><br>';
        echo '<input type="submit" name="search_student" value="Search">';
        echo '</form>';
        echo '</div>';
    }

    echo '</div>';
    return ob_get_clean();
}


// Register shortcode to display student results
add_shortcode('dm_student_result', 'display_student_results_shortcode');