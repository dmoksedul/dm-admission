<?php 
// admission form
function dm_admission_page() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_student']) && check_admin_referer('add_student', 'add_student_nonce')) {
            // Extract and sanitize data from the form
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
            $student_religion = sanitize_text_field($_POST['student_religion']);
            $student_nid = sanitize_text_field($_POST['student_nid']);
            $student_present_address = sanitize_text_field($_POST['student_present_address']);
            $student_permanent_address = sanitize_text_field($_POST['student_permanent_address']);
            $student_city = sanitize_text_field($_POST['student_city']);
            $student_state = sanitize_text_field($_POST['student_state']);
            $student_father_name = sanitize_text_field($_POST['student_father_name']);
            $student_parent_relation = sanitize_text_field($_POST['student_parent_relation']);
            $student_previous_institute_name = sanitize_text_field($_POST['student_previous_institute_name']);
            $student_previous_institute_qualification = sanitize_text_field($_POST['student_previous_institute_qualification']);
            $student_previous_institute_remarks = sanitize_text_field($_POST['student_previous_institute_remarks']);
            $student_parent_name = sanitize_text_field($_POST['student_parent_name']);
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
                && !empty($student_religion) && !empty($student_nid) && !empty($student_present_address)
                && !empty($student_permanent_address) && !empty($student_city) && !empty($student_state)
                && !empty($student_father_name) && !empty($student_previous_institute_name) && !empty($student_previous_institute_qualification) && !empty($student_previous_institute_remarks) && !empty($student_parent_name) && !empty($student_parent_relation) && !empty($student_mother_name) && !empty($student_parent_occupation)
                && !empty($student_parent_income) && !empty($student_parent_education) && !empty($student_parent_email)
                && !empty($student_parent_number) && !empty($student_parent_address) && !empty($student_parent_city)
                && !empty($student_parent_state)) {
                
                // Change the post status to 'pending'
                $post_status = 'pending';

                // Create a new student post and publish it directly
                $post_id = wp_insert_post(array(
                    'post_title' => $student_first_name . ' ' . $student_last_name,
                    'post_content' => '',
                    'post_type' => 'students',
                    'post_status' => $post_status, // Publish the student admission directly
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
                    update_post_meta($post_id, 'student_religion', $student_religion);
                    update_post_meta($post_id, 'student_nid', $student_nid);
                    update_post_meta($post_id, 'student_present_address', $student_present_address);
                    update_post_meta($post_id, 'student_permanent_address', $student_permanent_address);
                    update_post_meta($post_id, 'student_city', $student_city);
                    update_post_meta($post_id, 'student_state', $student_state);
                    update_post_meta($post_id, 'student_previous_institute_name', $student_previous_institute_name);
                    update_post_meta($post_id, 'student_previous_institute_qualification', $student_previous_institute_qualification);
                    update_post_meta($post_id, 'student_previous_institute_remarks', $student_previous_institute_remarks);
                    update_post_meta($post_id, 'student_parent_name', $student_parent_name);
                    update_post_meta($post_id, 'student_parent_relation', $student_parent_relation);
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
                }
            }
            // Set the approval status to 'pending' for admin review
            update_post_meta($post_id, 'approval_status', 'pending');

            // Handle image uploads (student image and parent image)
            if (isset($_FILES['student_image']) && !empty($_FILES['student_image']['name'])) {
                // Ensure the media functions are available
                require_once(ABSPATH . 'wp-admin/includes/media.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/image.php');

                $attachment_id = media_handle_upload('student_image', $post_id);
                if (!is_wp_error($attachment_id)) {
                    set_post_thumbnail($post_id, $attachment_id);
                }
            }

            // Handle parent image upload
            if (isset($_FILES['student_parent_image']) && !empty($_FILES['student_parent_image']['name'])) {
                // Ensure the media functions are available
                require_once(ABSPATH . 'wp-admin/includes/media.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/image.php');

                $parent_attachment_id = media_handle_upload('student_parent_image', $post_id);
                if (!is_wp_error($parent_attachment_id)) {
                    // You can save parent image attachment ID in post meta here if needed.
                }
            }

            // Handle document upload
            if (isset($_FILES['student_documents']) && !empty($_FILES['student_documents']['name'])) {
                // Ensure the media functions are available
                require_once(ABSPATH . 'wp-admin/includes/media.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/image.php');

                $document_attachment_id = media_handle_upload('student_documents', $post_id);
                if (!is_wp_error($document_attachment_id)) {
                    // You can save document attachment ID in post meta here if needed.
                }
            }
        }
    }

    ?>
    <div id="dashboard_admission_form_box">
        <div class="wrap">
            <h3 style="text-align:center;margin:10px 0px;font-weight:bold; color: #08A88A">New Admission</h3>
            <!-- List of Students for Admission -->
            <form class="admission_form_box" method="post" enctype="multipart/form-data">
                <!-- Institute Details Section -->
                <div>
                    <h3>Institute Details</h3>
                    <div class="institute_details_form">
                        <input readonly type="text" name="institute_name" value="<?php bloginfo( 'title' )  ?>" placeholder="Institute Name" required>
                        <input type="text" name="class" placeholder="Class" required>
                        <select name="section" id="" required placeholder="Select Section">
                            <option>Select Section</option>
                            <option value="Group A">Group A</option>
                            <option value="Group B">Group B</option>
                            <option value="Group C">Group C</option>
                            <option value="Group D">Group D</option>
                        </select>
                        <input type="text" name="admission_date" placeholder="Admission Date" required value="<?php echo date('d-m-Y'); ?>">
                        <select name="category" id="" required placeholder="Select Category">
                            <option>Select Category </option>
                            <option value="Science">Science</option>
                            <option value="Arts">Arts</option>
                            <option value="Commerce">Commerce</option>
                        </select>
                    </div>

                    <!-- Student Details Section -->
                    <h3>Student Details</h3>
                    <div class="student_details_form">
                        <input type="text" name="student_first_name" placeholder="First Name" required>
                        <input type="text" name="student_last_name" placeholder="Last Name" required>
                        <select name="student_gender" id="" required placeholder="Select Gender">
                            <option>Select Gender</option>
                            <option value="Female">Female</option>
                            <option value="Male">Male</option>
                            <option value="Others">Others</option>
                        </select>
                        <input type="date" name="student_birthdate" placeholder="Birthday" required>
                        <select name="student_blood_group" id="" required placeholder="Select Blood Group">
                            <option>Select Blood Group</option>
                            <option value="A+">A+</option>
                            <option value="B+">B+</option>
                            <option value="AB+">AB+</option>
                        </select>
                        <input type="text" name="student_phone" placeholder="Student Phone Number" required>
                        <input type="text" name="student_email" placeholder="Student Email" required>
                        <input type="text" name="student_religion" placeholder="Religion" required>
                        <input type="text" name="student_nid" placeholder="Birth Certificate or NID No" required>
                        <input type="text" name="student_present_address" placeholder="Present Address" required>
                        <input type="text" name="student_permanent_address" placeholder="Permanent Address" required>
                        <input type="text" name="student_city" placeholder="City" required>
                        <input type="text" name="student_state" placeholder="State" required>
                        <input type="file" name="student_image" accept="image/*">
                    </div>

                    <!-- Student Parent Details Section -->
                    <h3>Previous Institute Details</h3>
                    <div class="student_previous_details_form">
                        <input type="text" name="student_previous_institute_name" placeholder="Institute Name" required>
                        <input type="text" name="student_previous_institute_qualification" placeholder="Qualification" required>
                        <input type="text" name="student_previous_institute_remarks" placeholder="Remarks" required>
                    </div>
                    <h3>Student Parent Details</h3>
                    <div class="student_parent_form">
                        <input type="text" name="student_parent_name" placeholder="Guardian Name" required>
                        <input type="text" name="student_parent_relation" placeholder="Relation" required>
                        <input type="text" name="student_father_name" placeholder="Father's Name" required>
                        <input type="text" name="student_mother_name" placeholder="Mother's Name" required>
                        <input type="text" name="student_parent_occupation" placeholder="Occupation" required>
                        <input type="text" name="student_parent_income" placeholder="Income" required>
                        <input type="text" name="student_parent_education" placeholder="Education" required>
                        <input type="text" name="student_parent_email" placeholder="Guardian Email" required>
                        <input type="text" name="student_parent_number" placeholder="Guardian Mobile No" required>
                        <textarea type="text" name="student_parent_address" placeholder="Guardian Address" required></textarea>
                        <input type="text" name="student_parent_city" placeholder="Guardian City" required>
                        <input type="text" name="student_parent_state" placeholder="Guardian State" required>
                        <input type="file" name="student_parent_image" accept="image/*">
                    </div>
                </div>
                <div>
                    <!-- Upload Documents Section -->
                <h3>Upload Documents</h3>
                <input style="width:100%;" type="file" name="student_documents" placeholder="Add Documents" required>
                </div>
                

                <div id="submit_btn_box">
                <input id="admission_submit_button" type="submit" name="add_student" value="Add Student">
                <?php wp_nonce_field('add_student', 'add_student_nonce'); ?>
                </div>
            </form>
        </div>
    </div>
    <?php
}

