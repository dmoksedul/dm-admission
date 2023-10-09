<?php
// Include WordPress database connection
global $wpdb;
$table_name = $wpdb->prefix . 'dm_students';

// Check if the form is submitted for updating student information
if (isset($_POST['update_student'])) {
    // Get the values submitted in the form for student information
    // Assuming you have already defined the 'sanitize_text_field' and 'sanitize_textarea_field' functions

// Sanitize and format various input fields
// $institute_name = sanitize_text_field($_POST['institute_name']);
// $class = sanitize_text_field($_POST['class']);
// $section = sanitize_text_field($_POST['section']);
// $admission_date = sanitize_text_field($_POST['admission_date']);
// $category = sanitize_text_field($_POST['category']);
$student_first_name = sanitize_text_field($_POST['student_first_name']);
$student_last_name = sanitize_text_field($_POST['student_last_name']);
$student_gender = sanitize_text_field($_POST['student_gender']);
$student_birthdate = sanitize_text_field($_POST['student_birthdate']);
$student_blood_group = sanitize_text_field($_POST['student_blood_group']);
$student_phone_number = sanitize_text_field($_POST['student_phone_number']);
$student_email = sanitize_email($_POST['student_email']);
$student_religion = sanitize_text_field($_POST['student_religion']);
$student_nid = sanitize_text_field($_POST['student_nid']);
$student_present_address = sanitize_textarea_field($_POST['student_present_address']);
$student_permanent_address = sanitize_textarea_field($_POST['student_permanent_address']);
$student_city = sanitize_text_field($_POST['student_city']);
$student_state = sanitize_text_field($_POST['student_state']);
// $student_previous_institute_name = sanitize_text_field($_POST['student_previous_institute_name']);
// $student_previous_institute_qualification = sanitize_text_field($_POST['student_previous_institute_qualification']);
// $student_previous_institute_remarks = sanitize_textarea_field($_POST['student_previous_institute_remarks']);
$student_parent_name = sanitize_text_field($_POST['student_parent_name']);
// $student_parent_relation = sanitize_text_field($_POST['student_parent_relation']);
// $student_father_name = sanitize_text_field($_POST['student_father_name']);
// $student_mother_name = sanitize_text_field($_POST['student_mother_name']);
// $student_parent_occupation = sanitize_text_field($_POST['student_parent_occupation']);
// $student_parent_income = sanitize_text_field($_POST['student_parent_income']);
// $student_parent_education = sanitize_text_field($_POST['student_parent_education']);
// $student_parent_email = sanitize_email($_POST['student_parent_email']);
// $student_parent_number = sanitize_text_field($_POST['student_parent_number']);
// $student_parent_address = sanitize_textarea_field($_POST['student_parent_address']);
// $student_parent_city = sanitize_text_field($_POST['student_parent_city']);
// $student_parent_state = sanitize_text_field($_POST['student_parent_state']);
$student_registration_number = sanitize_text_field($_POST['student_registration_number']);

// Now, you have sanitized and formatted values for each of these variables, and you can use them in your application as needed.

    

    // Prepare data for updating student information
    $data = array(
        // 'institute_name' => $institute_name,
    // 'class' => $class,
    // 'section' => $section,
    // 'admission_date' => $admission_date,
    // 'category' => $category,
    'student_first_name' => $student_first_name,
    'student_last_name' => $student_last_name,
    'student_gender' => $student_gender,
    'student_birthdate' => $student_birthdate,
    'student_blood_group' => $student_blood_group,
    'student_phone_number' => $student_phone_number,
    'student_email' => $student_email,
    'student_religion' => $student_religion,
    'student_nid' => $student_nid,
    'student_present_address' => $student_present_address,
    'student_permanent_address' => $student_permanent_address,
    'student_city' => $student_city,
    'student_state' => $student_state,
    // 'student_previous_institute_name' => $student_previous_institute_name,
    // 'student_previous_institute_qualification' => $student_previous_institute_qualification,
    // 'student_previous_institute_remarks' => $student_previous_institute_remarks,
    // 'student_parent_name' => $student_parent_name,
    // 'student_parent_relation' => $student_parent_relation,
    // 'student_father_name' => $student_father_name,
    // 'student_mother_name' => $student_mother_name,
    // 'student_parent_occupation' => $student_parent_occupation,
    // 'student_parent_income' => $student_parent_income,
    // 'student_parent_education' => $student_parent_education,
    // 'student_parent_email' => $student_parent_email,
    // 'student_parent_number' => $student_parent_number,
    // 'student_parent_address' => $student_parent_address,
    // 'student_parent_city' => $student_parent_city,
    // 'student_parent_state' => $student_parent_state,
    'student_registration_number' => $student_registration_number,
);


    // Handle image upload to the media library if a new image is provided
    if (!empty($_FILES['new_student_image']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('new_student_image', 0);

        if (is_wp_error($attachment_id)) {
            echo '<div class="error notice"><p>Error uploading the new image: ' . esc_html($attachment_id->get_error_message()) . '</p></div>';
        } else {
            // Update the student record with the new image attachment ID
            $data['student_image'] = $attachment_id;
        }
    }

    // Define the WHERE clause for updating student information
    $where = array('id' => $student_id);

    // Update the student record in the database, including the image and document IDs
    $updated = $wpdb->update($table_name, $data, $where);

    if ($updated !== false) {
        echo '<div class="updated notice"><p>Student information updated successfully.</p></div>';
    } else {
        echo '<div class="error notice"><p>Error updating student information.</p></div>';
    }
}

// Fetch the student record to pre-fill the form
$student_id = intval($_GET['student_id']);
$student = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $student_id));
?>

<div class="wrap">
    <h2>Edit Student</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="student_id" value="<?php echo esc_attr($student->id); ?>">
        
        <!-- Student Registration Number -->
        <div class="input_box">
            <label for="student_registration_number">Student Registration Number:</label>
            <input type="text" name="student_registration_number" id="student_registration_number" value="<?php echo esc_attr($student->student_registration_number); ?>" required>
        </div>
        <!-- Student ID Number -->
        <div class="input_box">
            <label for="student_id_number">Student ID Number:</label>
            <input type="text" name="student_id_number" readonly id="student_id_number" value="<?php echo esc_attr($student->student_id_number); ?>" required>
        </div>

        <!-- First Name -->
        <div class="input_box">
            <label for="student_first_name">First Name:</label>
            <input type="text" name="student_first_name" id="student_first_name" value="<?php echo esc_attr($student->student_first_name); ?>" required>
        </div>

        <!-- Last Name -->
        <div class="input_box">
            <label for="student_last_name">Last Name:</label>
            <input type="text" name="student_last_name" id="student_last_name" value="<?php echo esc_attr($student->student_last_name); ?>" required>
        </div>


        <!-- Gender -->
        <div class="input_box">
            <label for="student_gender">Gender:</label>
            <input type="text" name="student_gender" id="student_gender" value="<?php echo esc_attr($student->student_gender); ?>" required>
        </div>
        <!-- Birthday -->
        <div class="input_box">
            <label for="student_birthdate">Birthday:</label>
            <input type="date" name="student_birthdate" id="student_birthdate" value="<?php echo esc_attr($student->student_birthdate); ?>" required>
        </div>
        <!-- Blood Group -->
        <div class="input_box">
            <label for="student_blood_group">Blood Group:</label>
            <input type="text" name="student_blood_group" id="student_blood_group" value="<?php echo esc_attr($student->student_blood_group); ?>" required>
        </div>

        <!-- Phone Number -->
        <div class="input_box">
            <label for="student_phone_number">Phone Number:</label>
            <input type="tel" name="student_phone_number" id="student_phone_number" value="<?php echo esc_attr($student->student_phone_number); ?>" required>
        </div>
        <!-- Email Address -->
        <div class="input_box">
            <label for="student_email">Email:</label>
            <input type="email" name="student_email" id="student_email" value="<?php echo esc_attr($student->student_email); ?>" required>
        </div>
        <!-- Religion -->
        <div class="input_box">
            <label for="student_religion">Religion:</label>
            <input type="text" name="student_religion" id="student_religion" value="<?php echo esc_attr($student->student_religion); ?>" required>
        </div>
        <!-- student_nid -->
        <div class="input_box">
            <label for="student_nid">Student NID:</label>
            <input type="number" name="student_nid" id="student_nid" value="<?php echo esc_attr($student->student_nid); ?>" required>
        </div>
        <!-- student_ address -->
        <div class="input_box">
            <label for="student_present_address">Present Address:</label>
            <input type="text" name="student_present_address" id="student_present_address" value="<?php echo esc_attr($student->student_present_address); ?>" required>
        </div>
        <!-- student_permanent_address -->
        <div class="input_box">
            <label for="student_permanent_address">Permanent Address:</label>
            <input type="text" name="student_permanent_address" id="student_permanent_address" value="<?php echo esc_attr($student->student_permanent_address); ?>" required>
        </div>
        <!-- student_city -->
        <div class="input_box">
            <label for="student_city">Student City:</label>
            <input type="text" name="student_city" id="student_city" value="<?php echo esc_attr($student->student_city); ?>" required>
        </div>
        <!-- student_city -->
        <div class="input_box">
            <label for="student_state">Student State:</label>
            <input type="text" name="student_state" id="student_state" value="<?php echo esc_attr($student->student_state); ?>" required>
        </div>
            
        <!-- Parent Name -->
        <div class="input_box">
            <label for="student_parent_name">Parent Name:</label>
            <input type="text" name="student_parent_name" id="student_parent_name" value="<?php echo esc_attr($student->student_parent_name); ?>" required>
        </div>

        <!-- Current Student Image -->
        <div class="current_student_image">
            <?php
            $current_image_url = wp_get_attachment_url($student->student_image);
            if ($current_image_url) {
                echo '<img width="50" src="' . esc_url($current_image_url) . '" alt="Current Student Image">';
            } else {
                echo 'No image available.';
            }
            ?>
        </div>

        <!-- Upload New Student Image -->
        <div class="input_box">
            <label for="new_student_image">Upload New Student Image:</label>
            <input type="file" name="new_student_image" id="new_student_image">
        </div>

        <!-- Continue adding more fields as needed -->

        <div class="submit_button_box">
            <input type="submit" name="update_student" value="Update Student">
        </div>
    </form>
</div>
