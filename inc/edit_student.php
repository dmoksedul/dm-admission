<?php

// Display the student edit page
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
                    'student_blood_group' => sanitize_text_field($_POST['student_blood_group']), // New field
                    'student_id_number' => sanitize_text_field($_POST['student_id_number']), // Corrected placement
                    'student_session' => sanitize_text_field($_POST['student_session']), // Corrected placement
                    'student_registration_number' => sanitize_text_field($_POST['student_registration_number']), // Corrected placement
                    'student_roll_number' => intval($_POST['student_roll_number']),
                    'student_exam' => sanitize_text_field($_POST['student_exam']), // Corrected placement
                );

                // Handle Student Image Upload
                if (!empty($_FILES['student_image']['name'])) {
                    $image = $_FILES['student_image'];
                    $upload_dir = wp_upload_dir(); // Get the WordPress upload directory
                    $image_name = sanitize_file_name($image['name']); // Sanitize the file name
                    $image_path = $upload_dir['path'] . '/' . $image_name;

                    if (move_uploaded_file($image['tmp_name'], $image_path)) {
                        // Update the student image path in the $data array
                        $data['student_image'] = $image_path;
                    } else {
                        echo '<div class="error"><p>Error uploading student image.</p></div>';
                    }
                }

                // Handle Student Documents Upload
                if (!empty($_FILES['student_documents']['name'])) {
                    $documents = $_FILES['student_documents'];
                    $upload_dir = wp_upload_dir();
                    $documents_name = sanitize_file_name($documents['name']);
                    $documents_path = $upload_dir['path'] . '/' . $documents_name;

                    if (move_uploaded_file($documents['tmp_name'], $documents_path)) {
                        // Update the student documents path in the $data array
                        $data['student_documents'] = $documents_path;
                    } else {
                        echo '<div class="error"><p>Error uploading student documents.</p></div>';
                    }
                }

                // Update the student data in the database
                $wpdb->update(
                    $table_name,
                    $data,
                    array('id' => $student_id),
                    '%s', // Adjust the format here based on the data types in your table columns
                    array('%d') // Adjust the format here based on the data type of the 'id' column
                );

                echo '<div class="updated"><p>Student data updated successfully.</p></div>';
            }

            // Display the edit form with student data
// Display the edit form with student data
echo '<div class="wrap">';
echo '<h2>Edit Student</h2>';
echo '<form method="post" enctype="multipart/form-data">'; // Added enctype for file uploads

// Add form fields for all student data fields with current values

// Institute Name
echo '<label for="institute_name">Institute Name:</label>';
echo '<input type="text" name="institute_name" id="institute_name" value="' . esc_attr($student->institute_name) . '" >';


echo '<label for="student_id_number">Student ID Number:</label>';
echo '<input type="text" name="student_id_number" id="student_id_number" value="' . esc_attr($student->student_id_number) . '" >';

echo '<label for="student_session">Student Session:</label>';
echo '<input type="text" name="student_session" id="student_session" value="' . esc_attr($student->student_session) . '" >';

echo '<label for="student_registration_number">Registration Number:</label>';
echo '<input type="text" name="student_registration_number" id="student_registration_number" value="' . esc_attr($student->student_registration_number) . '" >';

echo '<label for="student_roll_number">Roll Number:</label>';
echo '<input type="number" name="student_roll_number" id="student_roll_number" value="' . esc_attr($student->student_roll_number) . '" >';

echo '<label for="student_exam">Exam:</label>';
echo '<input type="text" name="student_exam" id="student_exam" value="' . esc_attr($student->student_exam) . '" >';



// Class
echo '<label for="class">Class:</label>';
echo '<input type="text" name="class" id="class" value="' . esc_attr($student->class) . '" >';

// Section
echo '<label for="section">Section:</label>';
echo '<input type="text" name="section" id="section" value="' . esc_attr($student->section) . '" >';

// Admission Date
echo '<label for="admission_date">Admission Date:</label>';
echo '<input type="date" name="admission_date" id="admission_date" value="' . esc_attr($student->admission_date) . '" >';

// Category
echo '<label for="category">Category:</label>';
echo '<input type="text" name="category" id="category" value="' . esc_attr($student->category) . '" >';

// First Name
echo '<label for="student_first_name">First Name:</label>';
echo '<input type="text" name="student_first_name" id="student_first_name" value="' . esc_attr($student->student_first_name) . '" >';

// Last Name
echo '<label for="student_last_name">Last Name:</label>';
echo '<input type="text" name="student_last_name" id="student_last_name" value="' . esc_attr($student->student_last_name) . '" >';

// Gender
echo '<label for="student_gender">Gender:</label>';
echo '<input type="radio" name="student_gender" id="male" value="Male" ' . ($student->student_gender === 'Male' ? 'checked' : '') . ' > Male';
echo '<input type="radio" name="student_gender" id="female" value="Female" ' . ($student->student_gender === 'Female' ? 'checked' : '') . ' > Female';

// Birthdate
echo '<label for="student_birthdate">Birthdate:</label>';
echo '<input type="date" name="student_birthdate" id="student_birthdate" value="' . esc_attr($student->student_birthdate) . '" >';

// Blood Group (New Field)
echo '<label for="student_blood_group">Blood Group:</label>';
echo '<input type="text" name="student_blood_group" id="student_blood_group" value="' . esc_attr($student->student_blood_group) . '" >';

// Phone Number
echo '<label for="student_phone_number">Phone Number:</label>';
echo '<input type="tel" name="student_phone_number" id="student_phone_number" value="' . esc_attr($student->student_phone_number) . '" >';

// Email
echo '<label for="student_email">Email:</label>';
echo '<input type="email" name="student_email" id="student_email" value="' . esc_attr($student->student_email) . '" >';

// Religion
echo '<label for="student_religion">Religion:</label>';
echo '<input type="text" name="student_religion" id="student_religion" value="' . esc_attr($student->student_religion) . '" >';

// National ID
echo '<label for="student_nid">National ID:</label>';
echo '<input type="text" name="student_nid" id="student_nid" value="' . esc_attr($student->student_nid) . '" >';

// Present Address
echo '<label for="student_present_address">Present Address:</label>';
echo '<textarea name="student_present_address" id="student_present_address" rows="4">' . esc_textarea($student->student_present_address) . '</textarea>';

// Permanent Address
echo '<label for="student_permanent_address">Permanent Address:</label>';
echo '<textarea name="student_permanent_address" id="student_permanent_address" rows="4">' . esc_textarea($student->student_permanent_address) . '</textarea>';

// City
echo '<label for="student_city">City:</label>';
echo '<input type="text" name="student_city" id="student_city" value="' . esc_attr($student->student_city) . '" >';

// State
echo '<label for="student_state">State:</label>';
echo '<input type="text" name="student_state" id="student_state" value="' . esc_attr($student->student_state) . '" >';

// Previous Institute Name
echo '<label for="student_previous_institute_name">Previous Institute Name:</label>';
echo '<input type="text" name="student_previous_institute_name" id="student_previous_institute_name" value="' . esc_attr($student->student_previous_institute_name) . '" >';

// Previous Institute Qualification
echo '<label for="student_previous_institute_qualification">Previous Institute Qualification:</label>';
echo '<input type="text" name="student_previous_institute_qualification" id="student_previous_institute_qualification" value="' . esc_attr($student->student_previous_institute_qualification) . '" >';

// Previous Institute Remarks
echo '<label for="student_previous_institute_remarks">Previous Institute Remarks:</label>';
echo '<textarea name="student_previous_institute_remarks" id="student_previous_institute_remarks" rows="4">' . esc_textarea($student->student_previous_institute_remarks) . '</textarea>';

// Parent Name
echo '<label for="student_parent_name">Parent Name:</label>';
echo '<input type="text" name="student_parent_name" id="student_parent_name" value="' . esc_attr($student->student_parent_name) . '" >';

// Parent Relation
echo '<label for="student_parent_relation">Parent Relation:</label>';
echo '<input type="text" name="student_parent_relation" id="student_parent_relation" value="' . esc_attr($student->student_parent_relation) . '" >';

// Father Name
echo '<label for="student_father_name">Father Name:</label>';
echo '<input type="text" name="student_father_name" id="student_father_name" value="' . esc_attr($student->student_father_name) . '" >';

// Mother Name
echo '<label for="student_mother_name">Mother Name:</label>';
echo '<input type="text" name="student_mother_name" id="student_mother_name" value="' . esc_attr($student->student_mother_name) . '" >';

// Parent Occupation
echo '<label for="student_parent_occupation">Parent Occupation:</label>';
echo '<input type="text" name="student_parent_occupation" id="student_parent_occupation" value="' . esc_attr($student->student_parent_occupation) . '" >';

// Parent Income
echo '<label for="student_parent_income">Parent Income:</label>';
echo '<input type="text" name="student_parent_income" id="student_parent_income" value="' . esc_attr($student->student_parent_income) . '" >';

// Parent Education
echo '<label for="student_parent_education">Parent Education:</label>';
echo '<input type="text" name="student_parent_education" id="student_parent_education" value="' . esc_attr($student->student_parent_education) . '" >';

// Parent Email
echo '<label for="student_parent_email">Parent Email:</label>';
echo '<input type="email" name="student_parent_email" id="student_parent_email" value="' . esc_attr($student->student_parent_email) . '" >';

// Parent Phone Number
echo '<label for="student_parent_number">Parent Phone Number:</label>';
echo '<input type="tel" name="student_parent_number" id="student_parent_number" value="' . esc_attr($student->student_parent_number) . '" >';

// Parent Address
echo '<label for="student_parent_address">Parent Address:</label>';
echo '<textarea name="student_parent_address" id="student_parent_address" rows="4">' . esc_textarea($student->student_parent_address) . '</textarea>';

// Parent City
echo '<label for="student_parent_city">Parent City:</label>';
echo '<input type="text" name="student_parent_city" id="student_parent_city" value="' . esc_attr($student->student_parent_city) . '" >';

// Parent State
echo '<label for="student_parent_state">Parent State:</label>';
echo '<input type="text" name="student_parent_state" id="student_parent_state" value="' . esc_attr($student->student_parent_state) . '" >';

// Display the current student image if it exists
if (!empty($student->student_image_path)) {
    echo '<p>Current Image: <a href="' . esc_url($student->student_image_path) . '" target="_blank"><img src="' . esc_url($student->student_image_path) . '" alt="Student Image"></a></p>';
}

// Add form fields for image and documents uploads
echo '<label for="student_image">Upload New Student Image:</label>';
echo '<input type="file" name="student_image" id="student_image" accept="image/*">';

echo '<label for="student_documents">Upload New Student Documents (PDF, DOC, DOCX):</label>';
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