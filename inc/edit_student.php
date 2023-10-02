<?php
// student edit page


function edit_student_page() {
    if (isset($_GET['student_id'])) {
        $student_id = intval($_GET['student_id']); // Get the student ID from the URL parameter

        global $wpdb;
        $table_name = $wpdb->prefix . 'dm_students';

        // Retrieve the specific student's data based on the student ID
        $student = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $student_id));

        if ($student) {
            // Check if the form is submitted for updating student data
            if (isset($_POST['update_student'])) {
                // Sanitize and update student data in the database
                $data = array(
                    'institute_name' => sanitize_text_field($_POST['institute_name']),
                    'class' => sanitize_text_field($_POST['class']),
                    'section' => sanitize_text_field($_POST['section']),
                    'admission_date' => sanitize_text_field($_POST['admission_date']),
                    'category' => sanitize_text_field($_POST['category']),
                    'student_first_name' => sanitize_text_field($_POST['student_first_name']),
                    'student_last_name' => sanitize_text_field($_POST['student_last_name']),
                    'student_gender' => sanitize_text_field($_POST['student_gender']),
                    'student_birthdate' => sanitize_text_field($_POST['student_birthdate']),
                    'student_phone_number' => sanitize_text_field($_POST['student_phone_number']),
                    'student_email' => sanitize_email($_POST['student_email']),
                    'student_religion' => sanitize_text_field($_POST['student_religion']),
                    'student_nid' => sanitize_text_field($_POST['student_nid']),
                    'student_present_address' => sanitize_textarea_field($_POST['student_present_address']),
                    'student_permanent_address' => sanitize_textarea_field($_POST['student_permanent_address']),
                    'student_city' => sanitize_text_field($_POST['student_city']),
                    'student_state' => sanitize_text_field($_POST['student_state']),
                    'student_previous_institute_name' => sanitize_text_field($_POST['student_previous_institute_name']),
                    'student_previous_institute_qualification' => sanitize_text_field($_POST['student_previous_institute_qualification']),
                    'student_previous_institute_remarks' => sanitize_textarea_field($_POST['student_previous_institute_remarks']),
                    'student_parent_name' => sanitize_text_field($_POST['student_parent_name']),
                    'student_parent_relation' => sanitize_text_field($_POST['student_parent_relation']),
                    'student_father_name' => sanitize_text_field($_POST['student_father_name']),
                    'student_mother_name' => sanitize_text_field($_POST['student_mother_name']),
                    'student_parent_occupation' => sanitize_text_field($_POST['student_parent_occupation']),
                    'student_parent_income' => sanitize_text_field($_POST['student_parent_income']),
                    'student_parent_education' => sanitize_text_field($_POST['student_parent_education']),
                    'student_parent_email' => sanitize_email($_POST['student_parent_email']),
                    'student_parent_number' => sanitize_text_field($_POST['student_parent_number']),
                    'student_parent_address' => sanitize_textarea_field($_POST['student_parent_address']),
                    'student_parent_city' => sanitize_text_field($_POST['student_parent_city']),
                    'student_parent_state' => sanitize_text_field($_POST['student_parent_state']),
                );
                // Handle Student Image Upload
if (isset($_FILES['student_image']) && $_FILES['student_image']['error'] === 0) {
    $upload_dir = wp_upload_dir(); // Get the WordPress upload directory
    $image_filename = $_FILES['student_image']['name'];
    $image_temp_path = $_FILES['student_image']['tmp_name'];

    // Generate a unique file name to avoid overwriting
    $image_destination = $upload_dir['path'] . '/' . uniqid() . '_' . $image_filename;

    // Move the uploaded image to the destination directory
    if (move_uploaded_file($image_temp_path, $image_destination)) {
        // Update the student's image field in the database with the file path
        $data['student_image'] = $image_destination;
    }
}

// Handle Student Documents Upload
if (isset($_FILES['student_documents']) && $_FILES['student_documents']['error'] === 0) {
    $upload_dir = wp_upload_dir(); // Get the WordPress upload directory
    $document_filename = $_FILES['student_documents']['name'];
    $document_temp_path = $_FILES['student_documents']['tmp_name'];

    // Generate a unique file name to avoid overwriting
    $document_destination = $upload_dir['path'] . '/' . uniqid() . '_' . $document_filename;

    // Move the uploaded document to the destination directory
    if (move_uploaded_file($document_temp_path, $document_destination)) {
        // Update the student's document field in the database with the file path
        $data['student_documents'] = $document_destination;
    }
}

                // Update the student data in the database
                $wpdb->update(
                    $table_name,
                    $data,
                    array('id' => $student_id),
                    $format = null,
                    $where_format = null
                );

                echo '<div class="updated"><p>Student data updated successfully.</p></div>';
            }

            // Display the edit form with student data
            echo '<div class="wrap">';
            echo '<h2>Edit Student</h2>';
            echo '<form method="post">';
            
            // Add form fields for all student data fields with current values
            echo '<label for="institute_name">Institute Name:</label>';
            echo '<input type="text" name="institute_name" id="institute_name" value="' . esc_attr($student->institute_name) . '" >';
            
            echo '<label for="class">Class:</label>';
            echo '<input type="text" name="class" id="class" value="' . esc_attr($student->class) . '" >';

            echo '<label for="section">Section:</label>';
            echo '<input type="text" name="section" id="section" value="' . esc_attr($student->section) . '" >';

            echo '<label for="admission_date">Admission Date:</label>';
            echo '<input type="date" name="admission_date" id="admission_date" value="' . esc_attr($student->admission_date) . '" >';

            echo '<label for="category">Category:</label>';
            echo '<input type="text" name="category" id="category" value="' . esc_attr($student->category) . '" >';

            echo '<label for="student_first_name">First Name:</label>';
            echo '<input type="text" name="student_first_name" id="student_first_name" value="' . esc_attr($student->student_first_name) . '" >';

            echo '<label for="student_last_name">Last Name:</label>';
            echo '<input type="text" name="student_last_name" id="student_last_name" value="' . esc_attr($student->student_last_name) . '" >';

            echo '<label for="student_gender">Gender:</label>';
            echo '<input type="radio" name="student_gender" id="male" value="Male" ' . ($student->student_gender === 'Male' ? 'checked' : '') . ' > Male';
            echo '<input type="radio" name="student_gender" id="female" value="Female" ' . ($student->student_gender === 'Female' ? 'checked' : '') . ' >';

            echo '<label for="student_birthdate">Birthdate:</label>';
            echo '<input type="date" name="student_birthdate" id="student_birthdate" value="' . esc_attr($student->student_birthdate) . '" >';

            echo '<label for="student_phone_number">Phone Number:</label>';
            echo '<input type="tel" name="student_phone_number" id="student_phone_number" value="' . esc_attr($student->student_phone_number) . '" >';

            echo '<label for="student_email">Email:</label>';
            echo '<input type="email" name="student_email" id="student_email" value="' . esc_attr($student->student_email) . '" >';

            echo '<label for="student_religion">Religion:</label>';
            echo '<input type="text" name="student_religion" id="student_religion" value="' . esc_attr($student->student_religion) . '" >';

            echo '<label for="student_nid">National ID:</label>';
            echo '<input type="text" name="student_nid" id="student_nid" value="' . esc_attr($student->student_nid) . '" >';

            echo '<label for="student_present_address">Present Address:</label>';
            echo '<textarea name="student_present_address" id="student_present_address" rows="4" >' . esc_textarea($student->student_present_address) . '</textarea>';

            echo '<label for="student_permanent_address">Permanent Address:</label>';
            echo '<textarea name="student_permanent_address" id="student_permanent_address" rows="4" >' . esc_textarea($student->student_permanent_address) . '</textarea>';

            echo '<label for="student_city">City:</label>';
            echo '<input type="text" name="student_city" id="student_city" value="' . esc_attr($student->student_city) . '" >';

            echo '<label for="student_state">State:</label>';
            echo '<input type="text" name="student_state" id="student_state" value="' . esc_attr($student->student_state) . '" >';

            echo '<label for="student_previous_institute_name">Previous Institute Name:</label>';
            echo '<input type="text" name="student_previous_institute_name" id="student_previous_institute_name" value="' . esc_attr($student->student_previous_institute_name) . '" >';

            echo '<label for="student_previous_institute_qualification">Previous Institute Qualification:</label>';
            echo '<input type="text" name="student_previous_institute_qualification" id="student_previous_institute_qualification" value="' . esc_attr($student->student_previous_institute_qualification) . '" >';

            echo '<label for="student_previous_institute_remarks">Previous Institute Remarks:</label>';
            echo '<textarea name="student_previous_institute_remarks" id="student_previous_institute_remarks" rows="4" >' . esc_textarea($student->student_previous_institute_remarks) . '</textarea>';

            echo '<label for="student_parent_name">Parent Name:</label>';
            echo '<input type="text" name="student_parent_name" id="student_parent_name" value="' . esc_attr($student->student_parent_name) . '" >';

            echo '<label for="student_parent_relation">Parent Relation:</label>';
            echo '<input type="text" name="student_parent_relation" id="student_parent_relation" value="' . esc_attr($student->student_parent_relation) . '" >';

            echo '<label for="student_father_name">Father Name:</label>';
            echo '<input type="text" name="student_father_name" id="student_father_name" value="' . esc_attr($student->student_father_name) . '" >';

            echo '<label for="student_mother_name">Mother Name:</label>';
            echo '<input type="text" name="student_mother_name" id="student_mother_name" value="' . esc_attr($student->student_mother_name) . '" >';

            echo '<label for="student_parent_occupation">Parent Occupation:</label>';
            echo '<input type="text" name="student_parent_occupation" id="student_parent_occupation" value="' . esc_attr($student->student_parent_occupation) . '" >';

            echo '<label for="student_parent_income">Parent Income:</label>';
            echo '<input type="text" name="student_parent_income" id="student_parent_income" value="' . esc_attr($student->student_parent_income) . '" >';

            echo '<label for="student_parent_education">Parent Education:</label>';
            echo '<input type="text" name="student_parent_education" id="student_parent_education" value="' . esc_attr($student->student_parent_education) . '" >';

            echo '<label for="student_parent_email">Parent Email:</label>';
            echo '<input type="email" name="student_parent_email" id="student_parent_email" value="' . esc_attr($student->student_parent_email) . '" >';

            echo '<label for="student_parent_number">Parent Phone Number:</label>';
            echo '<input type="tel" name="student_parent_number" id="student_parent_number" value="' . esc_attr($student->student_parent_number) . '" >';

            echo '<label for="student_parent_address">Parent Address:</label>';
            echo '<textarea name="student_parent_address" id="student_parent_address" rows="4" >' . esc_textarea($student->student_parent_address) . '</textarea>';

            echo '<label for="student_parent_city">Parent City:</label>';
            echo '<input type="text" name="student_parent_city" id="student_parent_city" value="' . esc_attr($student->student_parent_city) . '" >';

            echo '<label for="student_parent_state">Parent State:</label>';
            echo '<input type="text" name="student_parent_state" id="student_parent_state" value="' . esc_attr($student->student_parent_state) . '" >';

            echo '<label for="student_image">Upload Student Image:</label>';
            echo '<input type="file" name="student_image" id="student_image" accept="image/*">';
            
            echo '<label for="student_documents">Upload Student Documents (PDF, DOC, DOCX):</label>';
            echo '<input type="file" name="student_documents" id="student_documents" accept=".pdf,.doc,.docx">';
            
            // Add a submit button for updating the student data
            echo '<input type="submit" name="update_student" value="Update Student">';
            echo '</form>';
            echo '</div>';
        } else {
            echo '<div class="error"><p>Student not found.</p></div>';
        }
    } else {
        echo '<div class="error"><p>Invalid request. Student ID not provided.</p></div>';
    }
}

?>