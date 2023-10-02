<?php
// 
// Create the admission form
function admission_form_page() {
    if (isset($_POST['add_student'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'dm_students';

        // Handle file uploads
        $uploaded_image_id = 0;
        $uploaded_document_id = 0;

        if (isset($_FILES['student_image']) && $_FILES['student_image']['error'] === 0) {
            $uploaded_image_id = media_handle_upload('student_image', 0);
        }

        if (isset($_FILES['student_documents']) && $_FILES['student_documents']['error'] === 0) {
            $uploaded_document_id = media_handle_upload('student_documents', 0);
        }

        // Check for errors in file uploads
        if (is_wp_error($uploaded_image_id)) {
            // Handle the error, e.g., display an error message
        }

        if (is_wp_error($uploaded_document_id)) {
            // Handle the error, e.g., display an error message
        }

        // Sanitize and validate form data here
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
            'student_image' => $uploaded_image_id, /* Store attachment ID */
            'student_documents' => $uploaded_document_id, /* Store attachment ID */
        );

        $wpdb->insert($table_name, $data);

        // Add success message or redirection here if needed
    }

    // Display the content of your plugin's admin page here
    echo '<div class="wrap">';
    echo '<h2>Admission Form Plugin</h2>';
    ?>
    <form method="post" enctype="multipart/form-data">
        <!-- Institute Name -->
        <label for="institute_name">Institute Name:</label>
        <input type="text" name="institute_name" id="institute_name" required>

        <!-- Class -->
        <label for="class">Class:</label>
        <input type="text" name="class" id="class" required>

        <!-- Section -->
        <label for="section">Section:</label>
        <input type="text" name="section" id="section" required>

        <!-- Admission Date -->
        <label for="admission_date">Admission Date:</label>
        <input type="date" name="admission_date" id="admission_date" required>

        <!-- Category -->
        <label for="category">Category:</label>
        <input type="text" name="category" id="category" required>

        <!-- Student First Name -->
        <label for="student_first_name">First Name:</label>
        <input type="text" name="student_first_name" id="student_first_name" required>

        <!-- Student Last Name -->
        <label for="student_last_name">Last Name:</label>
        <input type="text" name="student_last_name" id="student_last_name" required>

        <!-- Student Gender -->
        <label for="student_gender">Gender:</label>
        <input type="radio" name="student_gender" id="male" value="Male" required> Male
        <input type="radio" name="student_gender" id="female" value="Female" required> Female

        <!-- Student Birthdate -->
        <label for="student_birthdate">Birthdate:</label>
        <input type="date" name="student_birthdate" id="student_birthdate" required>

        <!-- Student Phone Number -->
        <label for="student_phone_number">Phone Number:</label>
        <input type="tel" name="student_phone_number" id="student_phone_number" required>

        <!-- Student Email -->
        <label for="student_email">Email:</label>
        <input type="email" name="student_email" id="student_email" required>

        <!-- Student Religion -->
        <label for="student_religion">Religion:</label>
        <input type="text" name="student_religion" id="student_religion" required>

        <!-- Student National ID -->
        <label for="student_nid">National ID:</label>
        <input type="text" name="student_nid" id="student_nid" required>

        <!-- Student Present Address -->
        <label for="student_present_address">Present Address:</label>
        <textarea name="student_present_address" id="student_present_address" rows="4" required></textarea>

        <!-- Student Permanent Address -->
        <label for="student_permanent_address">Permanent Address:</label>
        <textarea name="student_permanent_address" id="student_permanent_address" rows="4" required></textarea>

        <!-- Student City -->
        <label for="student_city">City:</label>
        <input type="text" name="student_city" id="student_city" required>

        <!-- Student State -->
        <label for="student_state">State:</label>
        <input type="text" name="student_state" id="student_state" required>

        <!-- Student Previous Institute Name -->
        <label for="student_previous_institute_name">Previous Institute Name:</label>
        <input type="text" name="student_previous_institute_name" id="student_previous_institute_name" required>

        <!-- Student Previous Institute Qualification -->
        <label for="student_previous_institute_qualification">Previous Institute Qualification:</label>
        <input type="text" name="student_previous_institute_qualification" id="student_previous_institute_qualification" required>

        <!-- Student Previous Institute Remarks -->
        <label for="student_previous_institute_remarks">Previous Institute Remarks:</label>
        <textarea name="student_previous_institute_remarks" id="student_previous_institute_remarks" rows="4" required></textarea>

        <!-- Parent Name -->
        <label for="student_parent_name">Parent Name:</label>
        <input type="text" name="student_parent_name" id="student_parent_name" required>

        <!-- Parent Relation -->
        <label for="student_parent_relation">Parent Relation:</label>
        <input type="text" name="student_parent_relation" id="student_parent_relation" required>

        <!-- Father Name -->
        <label for="student_father_name">Father Name:</label>
        <input type="text" name="student_father_name" id="student_father_name" required>

        <!-- Mother Name -->
        <label for="student_mother_name">Mother Name:</label>
        <input type="text" name="student_mother_name" id="student_mother_name" required>

        <!-- Parent Occupation -->
        <label for="student_parent_occupation">Parent Occupation:</label>
        <input type="text" name="student_parent_occupation" id="student_parent_occupation" required>

        <!-- Parent Income -->
        <label for="student_parent_income">Parent Income:</label>
        <input type="text" name="student_parent_income" id="student_parent_income" required>

        <!-- Parent Education -->
        <label for="student_parent_education">Parent Education:</label>
        <input type="text" name="student_parent_education" id="student_parent_education" required>

        <!-- Parent Email -->
        <label for="student_parent_email">Parent Email:</label>
        <input type="email" name="student_parent_email" id="student_parent_email" required>

        <!-- Parent Phone Number -->
        <label for="student_parent_number">Parent Phone Number:</label>
        <input type="tel" name="student_parent_number" id="student_parent_number" required>

        <!-- Parent Address -->
        <label for="student_parent_address">Parent Address:</label>
        <textarea name="student_parent_address" id="student_parent_address" rows="4" required></textarea>

        <!-- Parent City -->
        <label for="student_parent_city">Parent City:</label>
        <input type="text" name="student_parent_city" id="student_parent_city" required>

        <!-- Parent State -->
        <label for="student_parent_state">Parent State:</label>
        <input type="text" name="student_parent_state" id="student_parent_state" required>

        <!-- Upload Student Image -->
        <label for="student_image">Upload Student Image:</label>
        <input type="file" name="student_image" id="student_image" accept="image/*">

        <!-- Upload Student Documents -->
        <label for="student_documents">Upload Student Documents:</label>
        <input type="file" name="student_documents" id="student_documents" accept=".pdf,.doc,.docx">

        <input type="submit" name="add_student" value="Add Student">
    </form>
    <?php
    echo '</div>';
}


?>