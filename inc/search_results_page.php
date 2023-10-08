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
            <input type="submit" name="search_student" value="Apply">
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
            <select name="exam_name" id="exam_name" required>
            <option value="" selected disabled>Select </option>
            <option value="Half Year">Half Year </option>
            <option value="Anual">Anual </option>
        </select>
            <label for="student_roll_number">Student Roll Number:</label>
            <input type="text" name="student_roll_number" id="student_roll_number" value="<?php echo esc_attr($student_roll_number); ?>" readonly>
            <input type="submit" name="insert_student_data" value="Add Student" <?php if ($disableSubmit) echo 'disabled'; ?>>
        </form>
    </div>
    <?php
}

















// Add a shortcode for generating admit cards
function admit_card_shortcode() {
    ob_start(); // Start output buffering

    // Initialize variables for search results
    $student_name_query = '';
    $exam_query = '';
    $registration_number_query = '';
    $search_results = array();

    // Check if the search form has been submitted
    if (isset($_POST['search_students'])) {
        // Check if the keys exist in the $_POST array before accessing their values
        if (isset($_POST['student_name_query'])) {
            $student_name_query = sanitize_text_field($_POST['student_name_query']);
        }
        if (isset($_POST['exam_query'])) {
            $exam_query = sanitize_text_field($_POST['exam_query']);
        }
        if (isset($_POST['registration_number_query'])) {
            $registration_number_query = sanitize_text_field($_POST['registration_number_query']);
        }

        // Query both the dm_students_esar and dm_students tables to search for matching students
        global $wpdb;
        $esar_table_name = $wpdb->prefix . 'dm_students_esar';
        $students_table_name = $wpdb->prefix . 'dm_students';

        // Search in dm_students_esar table
        $esar_sql = $wpdb->prepare(
            "SELECT * FROM $esar_table_name WHERE student_name LIKE %s AND exam LIKE %s",
            '%' . $wpdb->esc_like($student_name_query) . '%',
            '%' . $wpdb->esc_like($exam_query) . '%'
        );
        $esar_results = $wpdb->get_results($esar_sql);

        // Search in dm_students table
        $students_sql = $wpdb->prepare(
            "SELECT student_image, student_father_name, student_mother_name, student_city, student_state, student_session FROM $students_table_name WHERE student_registration_number LIKE %s",
            '%' . $wpdb->esc_like($registration_number_query) . '%'
        );
        $students_results = $wpdb->get_results($students_sql);

        // Merge the results from both tables
        $search_results = array_merge($esar_results, $students_results);
    }
    ?>

    <div class="admit-card-search">
        <form method="post" action="">
            <label for="student_name_query">Search by Student Name:</label>
            <input type="text" name="student_name_query" id="student_name_query" value="<?php echo esc_attr($student_name_query); ?>">
            <label for="exam_query">Search by Exam:</label>
            <input type="text" name="exam_query" id="exam_query" value="<?php echo esc_attr($exam_query); ?>">
            <label for="registration_number_query">Search by Registration Number:</label>
            <input type="text" name="registration_number_query" id="registration_number_query" value="<?php echo esc_attr($registration_number_query); ?>">
            <input type="submit" name="search_students" value="Search">
        </form>
    </div>

    <?php
    // Display search results
    if (!empty($search_results)) {
        echo '<h2>Search Results:</h2>';
        echo '<ul>';
        foreach ($search_results as $result) {
            echo '<li>';
            if (isset($result->student_name)) {
                echo 'Student Name: ' . esc_html($result->student_name) . '<br>';
                echo 'Student Registration Number: ' . esc_html($result->student_registration_number) . '<br>';
                echo 'Student Roll Number: ' . esc_html($result->student_roll_number) . '<br>';
                echo 'Class: ' . esc_html($result->class) . '<br>';
                echo 'Exam: ' . esc_html($result->exam) . '<br>';
                echo 'Subject List: ' . esc_html($result->subject_list) . '<br>';
            }
            if (isset($result->student_image)) {
                // Fetch and display the student image (for dm_students table)
                $student_image = wp_get_attachment_image($result->student_image, 'thumbnail');
                echo 'Student Image: ' . $student_image . '<br>';
            }
            if (isset($result->student_father_name)) {
                echo 'Father\'s Name: ' . esc_html($result->student_father_name) . '<br>';
                echo 'Mother\'s Name: ' . esc_html($result->student_mother_name) . '<br>';
                echo 'Location (City, State): ' . esc_html($result->student_city) . ', ' . esc_html($result->student_state) . '<br>';
                echo 'Session: ' . esc_html($result->student_session) . '<br>';
            }
            echo '</li>';
        }
        echo '</ul>';
    } elseif ($student_name_query !== '' || $exam_query !== '' || $registration_number_query !== '') {
        echo '<p>No matching students found.</p>';
    }
    ?>

    <?php
    return ob_get_clean(); // Return the buffered output
}
add_shortcode('admit_card', 'admit_card_shortcode');

