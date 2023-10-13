<?php

// student admit card print functions here

// Add a shortcode for generating admit cards
function student_admit_card_search() {
    $plugin_dir_path = plugin_dir_path(__FILE__);
    $institute_banner = plugin_dir_url($plugin_dir_path) . 'assets/img/banner.png';
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

        // Query the dm_students_esar table to search for matching students
        global $wpdb;
        $esar_table_name = $wpdb->prefix . 'dm_students_esar';

        $sql = $wpdb->prepare(
            "SELECT * FROM $esar_table_name WHERE student_name LIKE %s AND exam LIKE %s AND student_registration_number LIKE %s",
            '%' . $wpdb->esc_like($student_name_query) . '%',
            '%' . $wpdb->esc_like($exam_query) . '%',
            '%' . $wpdb->esc_like($registration_number_query) . '%'
        );

        $search_results = $wpdb->get_results($sql);
    }
    ?>
    <div class="admit_card_search">
        <form method="post" action="">
            <div>
                <label for="student_name_query">Student Name:</label>
                <input type="text" name="student_name_query" id="student_name_query" value="<?php echo esc_attr($student_name_query); ?>" required>
            </div>

            <div>
                <label for="exam_query">Exam:</label>
                <select name="exam_query" id="exam_query" required>
                    <option value="" selected disabled>Select Exam</option>
                    <option value="Annual" <?php selected($exam_query, 'Annual'); ?>>Annual</option>
                    <option value="Half Year" <?php selected($exam_query, 'Half Year'); ?>>Half Year</option>
                </select>
            </div>

            <div>
                <label for="registration_number_query">Registration Number:</label>
                <input type="text" name="registration_number_query" id="registration_number_query" value="<?php echo esc_attr($registration_number_query); ?>" required>
            </div>

            <div>
                <input type="submit" name="search_students" value="Submit">
            </div>
        </form>
    </div>

    <?php

    // Display search results
    if (!empty($search_results)) {
        echo '<br/>';
        echo '<br/>';
        echo '<ul>';
        foreach ($search_results as $result) {
            // Retrieve additional information from dm_students table
            $dm_students_table = $wpdb->prefix . 'dm_students';
            $student_data = $wpdb->get_row($wpdb->prepare(
                "SELECT student_father_name, student_mother_name, student_city, student_state, student_birthdate, student_gender, institute_name, student_session, student_image FROM $dm_students_table WHERE student_registration_number = %s",
                $result->student_registration_number
            ));

            echo '<div id="dm_admit_card_box">';
                echo '<div class="institute_details">
                    <img class="institute_logo" src="' . esc_url($institute_banner) . '" alt="College Name"/>
                    <h2 class="card_title">Admit Card</h2>
                    <h5>Junior School Certificate Exam 2023</h5>
                </div>';
                echo '<div>';
                echo '<div class="student_table_info_box">';
                echo '<table>';
                echo '<tr><td>Name of Student</td><td>: ' . esc_html($result->student_name) . '</td></tr>';

                // Check if student data is available
                if ($student_data) {
                    
                    echo '<tr><td>Father Name</td><td>: ' . esc_html($student_data->student_father_name) . '</td></tr>';
                    echo '<tr><td>Mother Name</td><td>: ' . esc_html($student_data->student_mother_name) . '</td></tr>';

                    // Display the formatted birthdate
                    $birthdate_timestamp = strtotime($student_data->student_birthdate);
                    $formatted_birthdate = date('d F Y', $birthdate_timestamp);
                    echo '<tr><td>Date of Birth</td><td>: ' . esc_html($formatted_birthdate) . '</td></tr>';
                    echo '<tr><td>Gender</td><td>: ' . esc_html($student_data->student_gender) . '</td></tr>';
                    echo '<tr><td>Name of Institute:</td><td>: ' . esc_html($student_data->institute_name) . '</td></tr>';
                    echo '<tr><td>Registration No</td><td>: ' . esc_html($result->student_registration_number) . '</td></tr>';
                    echo '<tr><td>Roll No</td><td>: ' . esc_html($result->student_roll_number) . '</td></tr>';
                    echo '<tr><td>Session</td><td>: ' . esc_html($student_data->student_session) . '</td></tr>';
                    echo '<tr><td>Exam Type</td><td>: ' . esc_html($result->exam) . '</td></tr>';
                    // Display student image if available
                    if (!empty($student_data->student_image)) {
                        $image_url = wp_get_attachment_url($student_data->student_image);
                        if ($image_url) {
                            echo '<img class="admit_student_image" width="150" src="' . esc_url($image_url) . '" alt="Student Image">';
                        }
                    }
                    echo '</table >';
                    echo '</div>';
                    
                    $subjects = explode(', ', esc_html($result->subject_list));
                    if (!empty($subjects)) {
                        echo '<div class="subject_table_row">'; // Add a container div for the row
                    
                        // First Table
                        echo '<div class="subject_table_admit_card">';
                        echo '<table>';
                    
                        // First Table Header
                        echo '<thead><tr><th>Serial</th><th>Name of Subject</th></tr></thead>';
                    
                        $totalSubjects = count($subjects);
                        for ($i = 0; $i < $totalSubjects; $i += 2) {
                            echo '<tr>';
                            echo '<td>' . ($i + 1) . '</td>';
                            echo '<td>' . $subjects[$i] . '</td>';
                            echo '</tr>';
                        }
                    
                        echo '</table>';
                        echo '</div>'; // Close the first table div
                    
                        // Second Table
                        echo '<div class="subject_table_admit_card">';
                        echo '<table>';
                    
                        // Second Table Header
                        echo '<thead><tr><th>Serial</th><th>Name of Subject</th></tr></thead>';
                    
                        for ($i = 1; $i < $totalSubjects; $i += 2) {
                            echo '<tr>';
                            echo '<td>' . ($i + 1) . '</td>';
                            echo '<td>' . $subjects[$i] . '</td>';
                            echo '</tr>';
                        }
                    
                        echo '</table>';
                        echo '</div>'; // Close the second table div
                    
                        echo '</div>'; // Close the row container
                        } else {
                            echo 'No subjects available.';
                        }
                    }
                // signature foooter box
                echo '<div class="signature_footer_box">';
                echo '
                    <div>
                        <p class="signature_fields"></p>
                        <p>Institute Signature</p>
                    </div>
                    <div>
                        <p class="signature_fields"></p>
                        <p>Student Signature</p>
                    </div>
                    <div>
                        <p class="signature_fields"></p>
                        <p>Principal Signature</p>
                    </div>
                ';
                echo '</div>';
                
                echo '<p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae quod iure illum est ullam eaque labore fugit similique id aliquam.</p>';
                echo '</div>';
            echo '</div>';
        }
        echo '<button id="admit_card_download_btn">Download PDF</button>';
        echo '</ul>';
    } elseif ($student_name_query !== '' || $exam_query !== '' || $registration_number_query !== '') {
        echo '<p class="message">No matching students found.</p>';
    }
    ?>
    <script>
        document.getElementById('admit_card_download_btn').addEventListener("click", function(){
            window.print();
        })
    </script>
    <?php
    return ob_get_clean(); // Return the buffered output
}

add_shortcode('dm_student_admit_card', 'student_admit_card_search');
