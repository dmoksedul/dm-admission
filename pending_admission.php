<?php
// Callback function for Pending Admission submenu
function dm_pending_admission_page() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['approve_admission']) && check_admin_referer('approve_admission', 'approve_admission_nonce')) {
            // Handle approve admission
            $post_id = isset($_POST['admission_id']) ? intval($_POST['admission_id']) : 0;
            if ($post_id) {
                // Approve the admission
                approve_admission($post_id);
            }
        } elseif (isset($_POST['delete_admission']) && check_admin_referer('delete_admission', 'delete_admission_nonce')) {
            // Handle delete admission
            $post_id = isset($_POST['admission_id']) ? intval($_POST['admission_id']) : 0;
            if ($post_id) {
                // Delete the admission
                delete_admission($post_id);
            }
        } elseif (isset($_POST['edit_admission']) && check_admin_referer('edit_admission', 'edit_admission_nonce')) {
            // Display the edit form for admission
            $post_id = isset($_POST['admission_id']) ? intval($_POST['admission_id']) : 0;
            if ($post_id) {
                display_edit_admission_form($post_id);
            }
        }
    }
    ?>
    <div class="wrap">
        <h2>Pending Admission</h2>

        <!-- Add code to display a list of pending admissions here -->
        <?php
        // Query pending admissions
        $args = array(
            'post_type' => 'students',
            'meta_query' => array(
                array(
                    'key' => 'approval_status',
                    'value' => 'pending', // Check for pending admissions
                ),
            ),
        );

        $pending_admissions = new WP_Query($args);

        if ($pending_admissions->have_posts()) {
            ?>
            <form method="post">
                <table class="wp-list-table widefat striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Class</th>
                            <th>Admission Date</th>
                            <th>Location</th>
                            <th>Phone Number</th>
                            <th>Father's Name</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 1;
                        while ($pending_admissions->have_posts()) : $pending_admissions->the_post();
                            $student_id = get_the_ID();
                            $student_name = get_the_title();
                            $class = get_post_meta($student_id, 'class', true);
                            $admission_date = get_post_meta($student_id, 'admission_date', true);
                            $location = get_post_meta($student_id, 'student_city', true) . ', ' . get_post_meta($student_id, 'student_state', true);
                            $phone_number = get_post_meta($student_id, 'student_phone', true);
                            $father_name = get_post_meta($student_id, 'student_father_name', true);
                            $student_image = get_the_post_thumbnail($student_id, 'thumbnail');
                            ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php echo $student_name; ?></td>
                                <td><?php echo $class; ?></td>
                                <td><?php echo $admission_date; ?></td>
                                <td><?php echo $location; ?></td>
                                <td><?php echo $phone_number; ?></td>
                                <td><?php echo $father_name; ?></td>
                                <td><?php echo $student_image; ?></td>
                                <td>
                                    <input type="hidden" name="admission_id" value="<?php echo get_the_ID(); ?>">
                                    <?php wp_nonce_field('approve_admission', 'approve_admission_nonce'); ?>
                                    <button type="submit" name="approve_admission" class="button button-primary">Approve</button>
                                    <?php wp_nonce_field('edit_admission', 'edit_admission_nonce'); ?>
                                    <button type="submit" name="edit_admission" class="button button-secondary">Edit</button>
                                    <?php wp_nonce_field('delete_admission', 'delete_admission_nonce'); ?>
                                    <button type="submit" name="delete_admission" class="button button-secondary">Delete</button>
                                    
                                </td>
                            </tr>
                            <?php
                            $count++;
                        endwhile;
                        ?>
                    </tbody>
                </table>
            </form>
            <?php
        } else {
            echo 'No pending admissions found.';
        }

        // Restore the global post object
        wp_reset_postdata();
        ?>
    </div>
    <?php
}

// Function to approve an admission
function approve_admission($post_id) {
    // Update the approval status to 'publish'
    update_post_meta($post_id, 'approval_status', 'publish');
    // Optionally, set the post status to 'publish'
    wp_update_post(array(
        'ID' => $post_id,
        'post_status' => 'publish',
    ));
}

// Function to delete an admission
function delete_admission($post_id) {
    // Delete the admission post
    wp_delete_post($post_id, true); // Set the second parameter to true to force delete

    // Additional code to delete associated custom field data, images, and documents
    // ...

    // Example: Delete associated student image (thumbnail)
    $thumbnail_id = get_post_thumbnail_id($post_id);
    if ($thumbnail_id) {
        wp_delete_attachment($thumbnail_id, true);
    }

    // Example: Delete associated parent image
    $parent_image_id = get_post_meta($post_id, 'parent_image_attachment_id', true);
    if ($parent_image_id) {
        wp_delete_attachment($parent_image_id, true);
    }

    // Example: Delete associated documents
    $document_id = get_post_meta($post_id, 'document_attachment_id', true);
    if ($document_id) {
        wp_delete_attachment($document_id, true);
    }
}

// Function to display the edit admission form with all form fields and pre-filled values
function display_edit_admission_form($admission_id) {
    // Retrieve the admission details for editing
    $student_id = $admission_id;
    $student_name = get_the_title($student_id);
    $class = get_post_meta($student_id, 'class', true);
    $admission_date = get_post_meta($student_id, 'admission_date', true);
    $location = get_post_meta($student_id, 'student_city', true) . ', ' . get_post_meta($student_id, 'student_state', true);
    $phone_number = get_post_meta($student_id, 'student_phone', true);
    $father_name = get_post_meta($student_id, 'student_father_name', true);
    $student_image = get_the_post_thumbnail($student_id, 'thumbnail');
    $student_first_name = get_post_meta($student_id, 'student_first_name', true);
    $student_last_name = get_post_meta($student_id, 'student_last_name', true);
    $student_gender = get_post_meta($student_id, 'student_gender', true);
    $student_birthdate = get_post_meta($student_id, 'student_birthdate', true);
    $student_blood_group = get_post_meta($student_id, 'student_blood_group', true);
    $student_email = get_post_meta($student_id, 'student_email', true);
    $student_religion = get_post_meta($student_id, 'student_religion', true);
    $student_nid = get_post_meta($student_id, 'student_nid', true);
    $student_present_address = get_post_meta($student_id, 'student_present_address', true);
    $student_permanent_address = get_post_meta($student_id, 'student_permanent_address', true);
    $student_city = get_post_meta($student_id, 'student_city', true);
    $student_state = get_post_meta($student_id, 'student_state', true);
    $student_parent_occupation = get_post_meta($student_id, 'student_parent_occupation', true);
    $student_parent_income = get_post_meta($student_id, 'student_parent_income', true);
    $student_parent_education = get_post_meta($student_id, 'student_parent_education', true);
    $student_parent_email = get_post_meta($student_id, 'student_parent_email', true);
    $student_parent_number = get_post_meta($student_id, 'student_parent_number', true);
    $student_parent_address = get_post_meta($student_id, 'student_parent_address', true);
    $student_parent_city = get_post_meta($student_id, 'student_parent_city', true);
    $student_parent_state = get_post_meta($student_id, 'student_parent_state', true);

    // Add more fields as needed

    ?>
    <div class="wrap">
        <h2>Edit Admission</h2>

        <form method="post">
            <!-- Add form fields for editing admission details -->
            <div>
                <h3>Institute Details</h3>
                <div class="institute_details_form">
                    <input readonly type="text" name="institute_name" value="<?php bloginfo('title'); ?>" placeholder="Institute Name" required>
                    <input type="text" name="class" placeholder="Class" value="<?php echo esc_attr($class); ?>" required>
                    <!-- Add more fields as needed -->
                </div>
            </div>

            <div>
                <h3>Student Details</h3>
                <div class="student_details_form">
                    <input type="text" name="student_first_name" placeholder="First Name" value="<?php echo esc_attr($student_first_name); ?>" required>
                    <input type="text" name="student_last_name" placeholder="Last Name" value="<?php echo esc_attr($student_last_name); ?>" required>
                    <!-- Add more fields as needed -->
                </div>
            </div>

            <div>
                <h3>Student Parent Details</h3>
                <div class="student_parent_form">
                    <input type="text" name="student_father_name" placeholder="Father's Name" value="<?php echo esc_attr($father_name); ?>" required>
                    <input type="text" name="student_mother_name" placeholder="Mother's Name" value="<?php echo esc_attr($student_mother_name); ?>" required>
                    <!-- Add more fields as needed -->
                </div>
            </div>

            <!-- Display the pre-filled student image -->
            <div>
                <h3>Student Image</h3>
                <?php echo $student_image; ?>
            </div>

            <!-- Upload Documents Section -->
            <div>
                <h3>Upload Documents</h3>
                <input style="width: 100%;" type="file" name="student_documents" placeholder="Add Documents" required>
            </div>

            <div id="submit_btn_box">
                <input id="admission_submit_button" type="submit" name="update_admission" value="Update Student">
                <?php wp_nonce_field('update_admission', 'update_admission_nonce'); ?>
                <input type="hidden" name="admission_id" value="<?php echo esc_attr($admission_id); ?>">
            </div>
        </form>
    </div>
    <?php
}

// Handle the submission of the edit form and update all admission details
function handle_edit_admission_submission() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_admission'])) {
        // Verify the nonce
        if (!isset($_POST['update_admission_nonce']) || !wp_verify_nonce($_POST['update_admission_nonce'], 'update_admission')) {
            wp_die('Security check failed.');
        }

        // Get admission ID from the form
        $admission_id = isset($_POST['admission_id']) ? intval($_POST['admission_id']) : 0;

        // Update all admission details
        if ($admission_id) {
            $student_name = sanitize_text_field($_POST['student_name']);
            $class = sanitize_text_field($_POST['class']);
            $admission_date = sanitize_text_field($_POST['admission_date']);
            $location = sanitize_text_field($_POST['location']);
            $phone_number = sanitize_text_field($_POST['phone_number']);
            $father_name = sanitize_text_field($_POST['father_name']);
            // Add more fields as needed
            $field1 = sanitize_text_field($_POST['field1']);
            $field2 = sanitize_text_field($_POST['field2']);
            $field3 = sanitize_text_field($_POST['field3']);

            // Update all admission details in custom fields
            update_post_meta($admission_id, 'student_name', $student_name);
            update_post_meta($admission_id, 'class', $class);
            update_post_meta($admission_id, 'admission_date', $admission_date);
            update_post_meta($admission_id, 'location', $location);
            update_post_meta($admission_id, 'phone_number', $phone_number);
            update_post_meta($admission_id, 'father_name', $father_name);
            // Update more fields as needed
            update_post_meta($admission_id, 'field1', $field1);
            update_post_meta($admission_id, 'field2', $field2);
            update_post_meta($admission_id, 'field3', $field3);

            // Redirect to the pending admission list
            wp_redirect(admin_url('admin.php?page=dm_pending_admission_submenu'));
            exit;
        }
    }
}
add_action('admin_init', 'handle_edit_admission_submission');


?>