<?php
// Callback function to display the submenu page content
function exam_submenu_page_content() {
    global $wpdb;

    // Initialize variables for student data
    $student_id_or_registration = ''; // Combined search input
    $student_name = '';
    $student_id_number = '';
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
        $student_id_or_registration = sanitize_text_field($_POST['student_id_or_registration']);
        
        // Query the dm_students table to retrieve student data based on ID number or registration number
        $table_name = $wpdb->prefix . 'dm_students';
        $student_data = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT student_id_number, student_first_name, student_last_name, student_registration_number, student_phone_number, subject_list, class, student_roll_number 
                FROM $table_name 
                WHERE student_id_number = %s OR student_registration_number = %s",
                $student_id_or_registration,
                $student_id_or_registration
            )
        );

        // If a student is found, store the data in variables
        if ($student_data) {
            $student_id_number = $student_data->student_id_number;
            $student_name = $student_data->student_first_name . ' ' . $student_data->student_last_name;
            $student_registration_number = $student_data->student_registration_number;
            $student_phone_number = $student_data->student_phone_number;
            $subject_list = $student_data->subject_list;
            $student_class = $student_data->class;
            $student_roll_number = $student_data->student_roll_number; // Assign student roll number

            // Check if the student exists in the dm_students_esar table based on multiple criteria
            $esar_table_name = $wpdb->prefix . 'dm_students_esar';

            // Get the selected exam name
            // $exam_name = sanitize_text_field($_POST['exam_name']);

            // Check if a record with the same combination exists
            $existing_student = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM $esar_table_name 
                    WHERE student_id_number = %s 
                    AND student_registration_number = %s 
                    AND class = %s 
                    AND exam = %s",
                    $student_id_number,
                    $student_registration_number,
                    $student_class,
                    $exam_name
                )
            );

            if ($existing_student) {
                echo '<div class="not_found_message"><p>Student Already Registerd. <button onclick="window.location.reload();" class="notfound_close_search">Try Again..</button></p></div>';
                $disableSubmit = true; // Disable the submit button
            } else {
                // Enable the submit button only when the student is not listed
                $disableSubmit = false;
            }
        } else {
            echo '<div class="not_found_message"><p>Student not found. <button onclick="window.location.reload();" class="notfound_close_search">Try Another</button></p></div>';
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

        // Check if a record with the same combination exists
        $esar_table_name = $wpdb->prefix . 'dm_students_esar';

        $existing_student = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $esar_table_name 
                WHERE student_id_number = %s 
                AND student_registration_number = %s 
                AND class = %s 
                AND exam = %s",
                $student_id_number,
                $student_registration_number,
                $student_class,
                $exam_name
            )
        );

        if ($existing_student) {
            echo '<div class="not_found_message"><p>Student Already Registerd. <button onclick="window.location.reload();" class="notfound_close_search">Try Another.</button></p></div>';
            $disableSubmit = true; // Disable the submit button
        } else {
            // Insert data into the dm_students_esar table
            $data = array(
                'student_id_number' => $student_id_number,
                'student_name' => $student_name,
                'student_registration_number' => $student_registration_number,
                'student_phone_number' => $student_phone_number,
                'subject_list' => $subject_list,
                'class' => $student_class,
                'exam' => $exam_name,
                'student_roll_number' => $student_roll_number,
                // Add other fields and their values as needed
            );

            $wpdb->insert($esar_table_name, $data);

            // Display a success message or handle errors
            echo '<div class="not_found_message"><p style="color:#056839">Registration successfully.<button onclick="window.location.reload();" class="notfound_close_search">Add Another.</button></p></div>';
            $disableSubmit = true;
        }
    }

    // Display the search form and search result
    ?>
    <div class="wrap">
        <h2>Exam Registration:</h2>
        <form id="exam_student_search_box" method="post" action="">
            <input type="text" name="student_id_or_registration" placeholder="Student ID or Registration No" id="student_id_or_registration" required>
            <input type="submit" name="search_student" value="Apply">
            <!-- <button type="submit" style="display:flex; justify-content:center; align-items:center;gap:10px" name="search_student">Apply <i class="fas fa-angle-right"></i></button> -->
        </form>
        <?php if ($message) : ?>
            <p><?php echo esc_html($message); ?></p>
        <?php endif; ?>
        <br/>
        <br/>
        <form id="student_exam_form" method="post" action="">
            <div>
                <div>
                <label for="student_name">Student Name:</label>
                <input type="text" name="student_name" id="student_name" placeholder="Auto Generate" value="<?php echo esc_attr($student_name); ?>" readonly>
                </div>
                <div>
                <label for="student_id_number">Student ID No:</label>
                <input type="text" name="student_id_number" id="student_id_number" placeholder="Auto Generate" value="<?php echo esc_attr($student_id_number); ?>" readonly>
                </div>
                <div>
                <label for="student_registration_number">Registration No:</label>
                <input type="text" name="student_registration_number" placeholder="Auto Generate" id="student_registration_number" value="<?php echo esc_attr($student_registration_number); ?>" readonly>
                </div>
                <div>
                <label for="student_roll_number">Roll Number:</label>
                <input type="text" name="student_roll_number" placeholder="Auto Generate" id="student_roll_number" value="<?php echo esc_attr($student_roll_number); ?>" readonly>
                </div>
                <div>
                <label for="student_phone_number">Phone Number:</label>
                <input type="text" name="student_phone_number" placeholder="Auto Generate" id="student_phone_number" value="<?php echo esc_attr($student_phone_number); ?>" readonly>
                </div>
                <div>
                <label for="subject_list">Subject List:</label>
                <input type="text" name="subject_list" id="subject_list" placeholder="Auto Generate" value="<?php echo esc_attr($subject_list); ?>" readonly>
                </div>
                <div>
                <label for="student_class">Class:</label>
                <input type="text" name="student_class" id="student_class" placeholder="Auto Generate" value="<?php echo esc_attr($student_class); ?>" readonly>
                </div>
                <div>
                <label for="exam_name">Exam Name:</label>
                <select name="exam_name" id="exam_name" required>
                    <option value="" selected disabled>Select</option>
                    <option value="Half Year">Half Year</option>
                    <option value="Annual">Annual</option>
                </select>
                </div>
                
            </div>
            <div class="submit_box">
            <input type="submit" name="insert_student_data" value="Add Student" <?php if ($disableSubmit) echo 'disabled'; ?>>
            </div>
            </div>
        </form>
    </div>
    <?php
}


// Callback function to display the submenu page content list of students
function exam_students_submenu_page_content() {
    global $wpdb;

    // Get data from the dm_students_esar table
    $esar_table_name = $wpdb->prefix . 'dm_students_esar';
    $students = $wpdb->get_results("SELECT * FROM $esar_table_name");

    ?>
    <div class="wrap">
        <h2>Exam Students List</h2>
        <table id="student_list_table_box" class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th style="width:50px">No</th>
                    <th style="width:120px">Student Image</th>
                    <th>Student Name</th>
                    <th>Registration Number</th>
                    <th>Roll Number</th>
                    <th>Class</th>
                    <th>Exam</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $serial_number = 1;
                foreach ($students as $student) {
                    // Get the student-specific image URL from the dm_students database
                    // Retrieve additional information from dm_students table
            $dm_students_table = $wpdb->prefix . 'dm_students';
            $student_data = $wpdb->get_row($wpdb->prepare(
                "SELECT student_image FROM $dm_students_table WHERE student_registration_number = %s",
                $student->student_registration_number
            ));

                    ?>
                    <tr>
                        <td><?php echo $serial_number; ?></td>
                        <td>
                            <?php // Display student image if available
                    if (!empty($student_data->student_image)) {
                        $image_url = wp_get_attachment_url($student_data->student_image);
                        if ($image_url) {
                            echo '<img width="50" class="admit_student_image" width="150" src="' . esc_url($image_url) . '" alt="Student Image">';
                        }
                    } ?>
                        </td>
                        <td><?php echo esc_html($student->student_name); ?></td>
                        <td><?php echo esc_html($student->student_registration_number); ?></td>
                        <td><?php echo esc_html($student->student_roll_number); ?></td>
                        <td><?php echo esc_html($student->class); ?></td>
                        <td><?php echo esc_html($student->exam); ?></td>
                    </tr>
                    <?php
                    $serial_number++;
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}









