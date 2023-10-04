<?php
// Include WordPress database connection
global $wpdb;
$table_name = $wpdb->prefix . 'dm_students';

// Check if the form is submitted for updating student information
if (isset($_POST['update_student'])) {
    // Get the values submitted in the form for student information
    $student_id = intval($_POST['student_id']);
    $first_name = sanitize_text_field($_POST['student_first_name']);
    $last_name = sanitize_text_field($_POST['student_last_name']);
    $id_number = sanitize_text_field($_POST['student_id_number']);
    $birthdate = sanitize_text_field($_POST['student_birthdate']);
    $phone_number = sanitize_text_field($_POST['student_phone_number']);
    $parent_name = sanitize_text_field($_POST['student_parent_name']);
    $city = sanitize_text_field($_POST['student_city']);
    $state = sanitize_text_field($_POST['student_state']);

    // Prepare data for updating student information
    $data = array(
        'student_first_name' => $first_name,
        'student_last_name' => $last_name,
        'student_id_number' => $id_number,
        'student_birthdate' => $birthdate,
        'student_phone_number' => $phone_number,
        'student_parent_name' => $parent_name,
        'student_city' => $city,
        'student_state' => $state,
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

        <!-- Student ID Number -->
        <div class="input_box">
            <label for="student_id_number">Student ID Number:</label>
            <input type="text" name="student_id_number" id="student_id_number" value="<?php echo esc_attr($student->student_id_number); ?>" required>
        </div>

        <!-- Birthday -->
        <div class="input_box">
            <label for="student_birthdate">Birthday:</label>
            <input type="date" name="student_birthdate" id="student_birthdate" value="<?php echo esc_attr($student->student_birthdate); ?>" required>
        </div>

        <!-- Phone Number -->
        <div class="input_box">
            <label for="student_phone_number">Phone Number:</label>
            <input type="tel" name="student_phone_number" id="student_phone_number" value="<?php echo esc_attr($student->student_phone_number); ?>" required>
        </div>

        <!-- Parent Name -->
        <div class="input_box">
            <label for="student_parent_name">Parent Name:</label>
            <input type="text" name="student_parent_name" id="student_parent_name" value="<?php echo esc_attr($student->student_parent_name); ?>" required>
        </div>

        <!-- Student City -->
        <div class="input_box">
            <label for="student_city">City:</label>
            <input type="text" name="student_city" id="student_city" value="<?php echo esc_attr($student->student_city); ?>" required>
        </div>

        <!-- Student State -->
        <div class="input_box">
            <label for="student_state">State:</label>
            <input type="text" name="student_state" id="student_state" value="<?php echo esc_attr($student->student_state); ?>" required>
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
