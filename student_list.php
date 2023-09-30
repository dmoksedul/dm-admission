<?php

function dm_students_page() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_student']) && check_admin_referer('add_student', 'add_student_nonce')) {
            // Extract data from the form
            $institute_name = sanitize_text_field($_POST['institute_name']);
            $class = sanitize_text_field($_POST['class']);
            $section = sanitize_text_field($_POST['section']);
            $admission_date = sanitize_text_field($_POST['admission_date']);
            $category = sanitize_text_field($_POST['category']);

            $student_first_name = sanitize_text_field($_POST['student_first_name']);
            $student_last_name = sanitize_text_field($_POST['student_last_name']);
            $student_gender = sanitize_text_field($_POST['student_gender']);
            $student_birthdate = sanitize_text_field($_POST['student_birthdate']);
            $student_phone = sanitize_text_field($_POST['student_phone']);
            $student_email = sanitize_text_field($_POST['student_email']);
            $student_blood_group = sanitize_text_field($_POST['student_blood_group']);
            $student_religion = sanitize_text_field($_POST['student_religion']);
            $student_nid = sanitize_text_field($_POST['student_nid']);
            $student_present_address = sanitize_text_field($_POST['student_present_address']);
            $student_permanent_address = sanitize_text_field($_POST['student_permanent_address']);
            $student_city = sanitize_text_field($_POST['student_city']);
            $student_state = sanitize_text_field($_POST['student_state']);

            $student_father_name = sanitize_text_field($_POST['student_father_name']);
            $student_mother_name = sanitize_text_field($_POST['student_mother_name']);
            $student_parent_occupation = sanitize_text_field($_POST['student_parent_occupation']);
            $student_parent_income = sanitize_text_field($_POST['student_parent_income']);
            $student_parent_education = sanitize_text_field($_POST['student_parent_education']);
            $student_parent_email = sanitize_text_field($_POST['student_parent_email']);
            $student_parent_number = sanitize_text_field($_POST['student_parent_number']);
            $student_parent_address = sanitize_textarea_field($_POST['student_parent_address']);
            $student_parent_city = sanitize_text_field($_POST['student_parent_city']);
            $student_parent_state = sanitize_text_field($_POST['student_parent_state']);

            // Process and save data to the database
            if (!empty($institute_name) && !empty($class) && !empty($section) && !empty($admission_date) && !empty($category)
                && !empty($student_first_name) && !empty($student_last_name) && !empty($student_gender) && !empty($student_birthdate) && !empty($student_phone) && !empty($student_email)
                && !empty($student_blood_group) && !empty($student_religion) && !empty($student_nid) && !empty($student_present_address)
                && !empty($student_permanent_address) && !empty($student_city) && !empty($student_state)
                && !empty($student_father_name) && !empty($student_mother_name) && !empty($student_parent_occupation)
                && !empty($student_parent_income) && !empty($student_parent_education) && !empty($student_parent_email)
                && !empty($student_parent_number) && !empty($student_parent_address) && !empty($student_parent_city)
                && !empty($student_parent_state)) {

                // Create a new student post
                $post_id = wp_insert_post(array(
                    'post_title' => $student_first_name . ' ' . $student_last_name,
                    'post_content' => '',
                    'post_type' => 'students',
                    'post_status' => 'publish',
                ));

                if ($post_id) {
                    // Update custom fields for student and institute details
                    update_post_meta($post_id, 'institute_name', $institute_name);
                    update_post_meta($post_id, 'class', $class);
                    update_post_meta($post_id, 'section', $section);
                    update_post_meta($post_id, 'admission_date', $admission_date);
                    update_post_meta($post_id, 'category', $category);

                    update_post_meta($post_id, 'student_first_name', $student_first_name);
                    update_post_meta($post_id, 'student_last_name', $student_last_name);
                    update_post_meta($post_id, 'student_gender', $student_gender);
                    update_post_meta($post_id, 'student_phone', $student_phone);
                    update_post_meta($post_id, 'student_email', $student_email);
                    update_post_meta($post_id, 'student_birthdate', $student_birthdate);
                    update_post_meta($post_id, 'student_blood_group', $student_blood_group);
                    update_post_meta($post_id, 'student_religion', $student_religion);
                    update_post_meta($post_id, 'student_nid', $student_nid);
                    update_post_meta($post_id, 'student_present_address', $student_present_address);
                    update_post_meta($post_id, 'student_permanent_address', $student_permanent_address);
                    update_post_meta($post_id, 'student_city', $student_city);
                    update_post_meta($post_id, 'student_state', $student_state);

                    update_post_meta($post_id, 'student_father_name', $student_father_name);
                    update_post_meta($post_id, 'student_mother_name', $student_mother_name);
                    update_post_meta($post_id, 'student_parent_occupation', $student_parent_occupation);
                    update_post_meta($post_id, 'student_parent_income', $student_parent_income);
                    update_post_meta($post_id, 'student_parent_education', $student_parent_education);
                    update_post_meta($post_id, 'student_parent_email', $student_parent_email);
                    update_post_meta($post_id, 'student_parent_number', $student_parent_number);
                    update_post_meta($post_id, 'student_parent_address', $student_parent_address);
                    update_post_meta($post_id, 'student_parent_city', $student_parent_city);
                    update_post_meta($post_id, 'student_parent_state', $student_parent_state);

                    // Handle image uploads (student image and parent image)
                    if (isset($_FILES['student_image']) && !empty($_FILES['student_image']['name'])) {
                        $attachment_id = media_handle_upload('student_image', $post_id);
                        if (!is_wp_error($attachment_id)) {
                            set_post_thumbnail($post_id, $attachment_id);
                        }
                    }

                    if (isset($_FILES['student_parent_image']) && !empty($_FILES['student_parent_image']['name'])) {
                        $parent_attachment_id = media_handle_upload('student_parent_image', $post_id);
                        if (!is_wp_error($parent_attachment_id)) {
                            // You can save parent image attachment ID in post meta here if needed.
                        }
                    }

                    // Handle document upload
                    if (isset($_FILES['student_documents']) && !empty($_FILES['student_documents']['name'])) {
                        $document_attachment_id = media_handle_upload('student_documents', $post_id);
                        if (!is_wp_error($document_attachment_id)) {
                            // You can save document attachment ID in post meta here if needed.
                        }
                    }
                }
            }
        } elseif (isset($_POST['delete_students']) && check_admin_referer('delete_students', 'delete_students_nonce')) {
            // Check if the "Delete Selected" button is clicked
            $students_to_delete = isset($_POST['students_to_delete']) ? $_POST['students_to_delete'] : array();

            foreach ($students_to_delete as $student_id) {
                // Delete the student post
                wp_delete_post($student_id, true); // Set the second parameter to true to force delete

                // Additional code to delete associated custom field data, images, and documents
                // ...

                // Example: Delete associated student image (thumbnail)
                $thumbnail_id = get_post_thumbnail_id($student_id);
                if ($thumbnail_id) {
                    wp_delete_attachment($thumbnail_id, true);
                }

                // Example: Delete associated parent image
                $parent_image_id = get_post_meta($student_id, 'parent_image_attachment_id', true);
                if ($parent_image_id) {
                    wp_delete_attachment($parent_image_id, true);
                }

                // Example: Delete associated documents
                $document_id = get_post_meta($student_id, 'document_attachment_id', true);
                if ($document_id) {
                    wp_delete_attachment($document_id, true);
                }
            }
        }
    }
    // Display the updated form structure with delete functionality
    ?>
    <div id="dashboard_notice_box">
        <div class="wrap">


            <!-- List of Students -->
            <h3>List of Students</h3>
            <form method="post">
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Serial Number</th>
                            <th>Student Name</th>
                            <th>Birthday</th>
                            <th>Phone Number</th>
                            <th>Father's Name</th>
                            <th>Location</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $args = array(
                            'post_type' => 'students',
                            'posts_per_page' => -1,
                        );

                        $student_query = new WP_Query($args);

                        if ($student_query->have_posts()) :
                            $serial_number = 1; // Initialize the serial number counter
                            while ($student_query->have_posts()) : $student_query->the_post();
                                $student_id = get_the_ID();
                                $student_name = get_the_title();
                                $student_birthdate = get_post_meta($student_id, 'student_birthdate', true);
                                $student_phone = get_post_meta($student_id, 'student_phone', true);
                                $father_name = get_post_meta($student_id, 'student_father_name', true);
                                $location = get_post_meta($student_id, 'student_city', true);
                                $student_image = get_the_post_thumbnail($student_id, 'thumbnail');
                                ?>
                                <tr>
                                    <td><input type="checkbox" name="students_to_delete[]" value="<?php echo $student_id; ?>"></td>
                                    <td><?php echo $serial_number; ?></td>
                                    <td><?php echo $student_name; ?></td>
                                    <td><?php echo $student_birthdate; ?></td>
                                    <td><?php echo $student_phone; ?></td>
                                    <td><?php echo $father_name; ?></td>
                                    <td><?php echo $location; ?></td>
                                    <td><?php echo $student_image; ?></td>
                                </tr>
                                <?php
                                $serial_number++; // Increment the serial number for the next student
                            endwhile;
                            wp_reset_postdata();
                        else :
                            ?>
                            <tr>
                                <td colspan="7">No students found.</td>
                            </tr>
                            <?php
                        endif;
                        ?>
                    </tbody>
                </table>
                <input type="submit" name="delete_students" value="Delete Selected">
                <?php wp_nonce_field('delete_students', 'delete_students_nonce'); ?>
            </form>
        </div>
    </div>
    <?php
}


?>