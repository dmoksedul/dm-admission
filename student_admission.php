<?php
function dm_admission_page_output() {
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

            // Create a new student post and publish it directly
            $post_id = wp_insert_post(array(
                'post_title' => $student_first_name . ' ' . $student_last_name,
                'post_content' => '',
                'post_type' => 'students',
                'post_status' => 'publish', // Publish the student admission directly
            ));


            if ($post_id) {
                // Update custom fields with admission data
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

                // Handle image uploads for student and parent
                $student_image = $_FILES['student_image'];
                $student_parent_image = $_FILES['student_parent_image'];

                if (!empty($student_image['name'])) {
                    // Handle student image upload
                    $upload_dir = wp_upload_dir(); // Get the upload directory
                    $image_filename = sanitize_file_name($student_first_name . '-' . $student_last_name . '-' . $student_image['name']);
                    $image_path = $upload_dir['path'] . '/' . $image_filename;

                    if (move_uploaded_file($student_image['tmp_name'], $image_path)) {
                        update_post_meta($post_id, 'student_image', $upload_dir['url'] . '/' . $image_filename);
                    }
                }

                if (!empty($student_parent_image['name'])) {
                    // Handle parent image upload
                    $upload_dir = wp_upload_dir(); // Get the upload directory
                    $parent_image_filename = sanitize_file_name($student_first_name . '-' . $student_last_name . '-parent-' . $student_parent_image['name']);
                    $parent_image_path = $upload_dir['path'] . '/' . $parent_image_filename;

                    if (move_uploaded_file($student_parent_image['tmp_name'], $parent_image_path)) {
                        update_post_meta($post_id, 'student_parent_image', $upload_dir['url'] . '/' . $parent_image_filename);
                    }
                }

                // Handle document upload
                $student_documents = $_FILES['student_documents'];

                if (!empty($student_documents['name'])) {
                    // Handle document upload
                    $upload_dir = wp_upload_dir(); // Get the upload directory
                    $document_filename = sanitize_file_name($student_first_name . '-' . $student_last_name . '-document-' . $student_documents['name']);
                    $document_path = $upload_dir['path'] . '/' . $document_filename;

                    if (move_uploaded_file($student_documents['tmp_name'], $document_path)) {
                        update_post_meta($post_id, 'student_documents', $upload_dir['url'] . '/' . $document_filename);
                    }
                }

                // Redirect to a thank you page or display a success message
            }
        }
    }


    ob_start();
    ?>
    <div id="dashboard_admission_form_box">
        <div class="wrap">
            <h2 style="text-align:center;margin:10px 0px;font-weight:bold; color: #08A88A">Student Admission</h2>
            <form class="upload_form_box" method="post" enctype="multipart/form-data">
                <!-- Institute, Student, and Parent Details Sections -->
                <!-- ...

                <input type="submit" name="add_student" value="Add Student">
                <?php wp_nonce_field('add_student', 'add_student_nonce'); ?>
            </form>

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
                    <h3>Student Parent Details</h3>
                    <div class="student_parent_form">
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
    return ob_get_clean();
}

add_shortcode('dm_student_admission', 'dm_admission_page_output');


?>