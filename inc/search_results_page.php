<?php


// Callback function to display the submenu page content
function exam_submenu_page_content() {
    global $wpdb;

    // Initialize variables for student data
    $student_id_number = '';
    $student_name = '';
    $student_registration_number = '';
    $student_phone_number = '';
    $subject_list = '';
    $student_class = '';
    $exam_name = '';
    $student_roll_number = ''; // Initialize student roll number

    // Initialize variable for messages and submit button status
    $message = '';
    $disableSubmit = true; // Default to disabled

    // Check if the search form has been submitted
    if (isset($_POST['search_student'])) {
        $student_id = sanitize_text_field($_POST['student_id']);
        // Query the dm_students table to retrieve student data based on $student_id
        $table_name = $wpdb->prefix . 'dm_students';
        $student_data = $wpdb->get_row("SELECT student_id_number, student_first_name, student_last_name, student_registration_number, student_phone_number, subject_list, class, student_roll_number FROM $table_name WHERE student_id_number = '$student_id'");

        // If a student is found, store the data in variables
        if ($student_data) {
            $student_id_number = $student_data->student_id_number;
            $student_name = $student_data->student_first_name . ' ' . $student_data->student_last_name;
            $student_registration_number = $student_data->student_registration_number;
            $student_phone_number = $student_data->student_phone_number;
            $subject_list = $student_data->subject_list;
            $student_class = $student_data->class;
            $student_roll_number = $student_data->student_roll_number; // Assign student roll number

            // Check if the student exists in the dm_students_esar table
            $esar_table_name = $wpdb->prefix . 'dm_students_esar';
            $existing_student = $wpdb->get_row("SELECT * FROM $esar_table_name WHERE student_id_number = '$student_id'");

            if ($existing_student) {
                $message = 'Student already listed in dm_students_esar.';
            } else {
                // Enable the submit button only when the student is not listed
                $disableSubmit = false;
            }
        } else {
            $message = 'Student not found.';
        }
    }

    // Handle inserting data into 'dm_students_esar' table
    if (isset($_POST['insert_student_data'])) {
        // Get the data from the form fields
        $student_id_number = sanitize_text_field($_POST['student_id_number']);
        $student_name = sanitize_text_field($_POST['student_name']);
        $student_registration_number = sanitize_text_field($_POST['student_registration_number']);
        $student_phone_number = sanitize_text_field($_POST['student_phone_number']);
        $subject_list = sanitize_text_field($_POST['subject_list']);
        $student_class = sanitize_text_field($_POST['student_class']);
        $exam_name = sanitize_text_field($_POST['exam_name']);
        $student_roll_number = sanitize_text_field($_POST['student_roll_number']); // Get student roll number

        // Insert data into the dm_students_esar table
        $esar_table_name = $wpdb->prefix . 'dm_students_esar';

        $data = array(
            'student_id_number' => $student_id_number,
            'student_name' => $student_name,
            'student_registration_number' => $student_registration_number,
            'student_phone_number' => $student_phone_number,
            'subject_list' => $subject_list,
            'class' => $student_class,
            'exam' => $exam_name,
            'student_roll_number' => $student_roll_number, // Add student roll number to the data
            // Add other fields and their values as needed
        );

        $wpdb->insert($esar_table_name, $data);

        // Display a success message or handle errors
        $message = 'Data inserted successfully into dm_students_esar table.';
        $disableSubmit = true;
    }

    // Display the search form and search result
    ?>
    <div class="wrap">
        <h2>Exam</h2>
        <form method="post" action="">
            <label for="student_id">Search Student by ID:</label>
            <input type="text" name="student_id" id="student_id" required>
            <input type="submit" name="search_student" value="Search">
        </form>
        <?php if ($message) : ?>
            <p><?php echo esc_html($message); ?></p>
        <?php endif; ?>
        <h3>Search Result:</h3>
        <form method="post" action="">
            <label for="student_id_number">Student ID Number:</label>
            <input type="text" name="student_id_number" id="student_id_number" value="<?php echo esc_attr($student_id_number); ?>" readonly>
            <label for="student_name">Student Name:</label>
            <input type="text" name="student_name" id="student_name" value="<?php echo esc_attr($student_name); ?>" readonly>
            <label for="student_registration_number">Student Registration Number:</label>
            <input type="text" name="student_registration_number" id="student_registration_number" value="<?php echo esc_attr($student_registration_number); ?>" readonly>
            <label for="student_phone_number">Student Phone Number:</label>
            <input type="text" name="student_phone_number" id="student_phone_number" value="<?php echo esc_attr($student_phone_number); ?>" readonly>
            <label for="subject_list">Subject List:</label>
            <input type="text" name="subject_list" id="subject_list" value="<?php echo esc_attr($subject_list); ?>" readonly>
            <label for="student_class">Class:</label>
            <input type="text" name="student_class" id="student_class" value="<?php echo esc_attr($student_class); ?>" readonly>
            <label for="exam_name">Exam Name:</label>
            <input type="text" name="exam_name" id="exam_name" required>
            <label for="student_roll_number">Student Roll Number:</label>
            <input type="text" name="student_roll_number" id="student_roll_number" value="<?php echo esc_attr($student_roll_number); ?>" readonly>
            <input type="submit" name="insert_student_data" value="Insert Data" <?php if ($disableSubmit) echo 'disabled'; ?>>
        </form>
    </div>
    <?php
}
