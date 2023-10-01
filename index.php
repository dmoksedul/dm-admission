<?php
/*
Plugin Name: Student Management Plugin
Description: A WordPress plugin for managing students.
Version: 1.0
Author: Your Name
*/

// Register a custom post type for Students
function add_dm_students_plugin_menu() {
    // Top-level menu item
    add_menu_page(
        'Students',
        'Students',
        'manage_options',
        'dm_students_plugin',
        'dm_students_page',
        'dashicons-businessman',
        6
    );

    // Submenu item for Admission
    add_submenu_page(
        'dm_students_plugin', // Parent slug
        'Admission', // Page title
        'Admission', // Menu title
        'manage_options',
        'dm_admission_submenu', // Menu slug
        'dm_admission_page' // Callback function for Admission submenu
    );

    // Submenu item for Pending Admission
    add_submenu_page(
        'dm_students_plugin', // Parent slug
        'Pending Admission', // Page title
        'Pending Admission', // Menu title
        'manage_options',
        'dm_pending_admission_submenu', // Menu slug
        'dm_pending_admission_page' // Callback function for Pending Admission submenu
    );
}
add_action('admin_menu', 'add_dm_students_plugin_menu');

// Include necessary files
include_once('admission_form.php');
include_once('student_list.php');
include_once('search.php');
include_once('pending_admission.php');



// Add this code to your custom plugin or theme's functions.php file

// Function to generate the admission form
function admission_form_shortcode() {
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

    ob_start();
    ?>
    <div id="dashboard_admission_form_box">
        <div class="wrap">
            <h3 style="text-align:left;margin:10px 0px;font-weight:600; border-bottom:2px solid #056839; display:inline-block; color: #056839">New Admission</h3>
            <!-- List of Students for Admission -->
            <form class="admission_form_box" method="post" enctype="multipart/form-data">
                <!-- Institute Details Section -->
                <div>
                    <h3>Institute Details</h3>
                    <div class="institute_details_form input_main_box">
                        <div class="input_box">
                            <label for="">Institute Name <span style="color:red">*</span> </label>
                            <input readonly type="text" name="institute_name" value="<?php bloginfo( 'title' )  ?>"  required>
                        </div>
                        <div class="input_box">
                            <label for="">Class <span style="color:red">*</span> </label>
                            <input type="text" name="class"  required>
                        </div>
                        <div class="input_box">
                            <label for="">Select Section <span style="color:red">*</span> </label>
                            <select name="section" id="" required >
                                <option>Select</option>
                                <option value="Group A">Group A</option>
                                <option value="Group B">Group B</option>
                                <option value="Group C">Group C</option>
                                <option value="Group D">Group D</option>
                            </select>
                        </div>
                        <div class="input_box">
                            <label for="">Admission Date <span style="color:red">*</span> </label>
                            <input type="text" name="admission_date"  required value="<?php echo date('d-m-Y'); ?>">
                        </div>
                        <div class="input_box">
                            <label for="">Category <span style="color:red">*</span> </label>
                            <select name="category" id="" required >
                                <option>Select </option>
                                <option value="Science">Science</option>
                                <option value="Arts">Arts</option>
                                <option value="Commerce">Commerce</option>
                            </select>
                        </div>
                    </div>

                    <!-- Student Details Section -->
                    <h3>Student Details</h3>
                    <div class="student_details_form input_main_box">
                        <div class="input_box">
                            <label for="">First Name <span style="color:red">*</span> </label>
                            <input type="text" name="student_first_name"  required>
                        </div>
                        <div class="input_box">
                            <label for="">Last Name <span style="color:red">*</span> </label>
                            <input type="text" name="student_last_name"  required>
                        </div>
                        <div class="input_box">
                            <label for="">Gender <span style="color:red">*</span> </label>
                            <select name="student_gender" id="" required >
                                <option>Select </option>
                                <option value="Female">Female</option>
                                <option value="Male">Male</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="input_box">
                            <label for="">Birthday <span style="color:red">*</span> </label>
                            <input type="date" name="student_birthdate"  required>
                        </div>
                        <div class="input_box">
                            <label for="">Select Blood Group <span style="color:red">*</span> </label>
                            <select name="student_blood_group" id="" required >
                                <option>Select</option>
                                <option value="A+">A+</option>
                                <option value="B+">B+</option>
                                <option value="AB+">AB+</option>
                            </select>
                        </div>
                        <div class="input_box">
                            <label for="">Student Phone Number <span style="color:red">*</span> </label>
                            <input type="text" name="student_phone"  required>
                        </div>
                        <div class="input_box">
                            <label for="">Student Email <span style="color:red">*</span> </label>
                            <input type="text" name="student_email"  required>
                        </div>
                        <div class="input_box">
                            <label for="">Religion <span style="color:red">*</span> </label>
                            <input type="text" name="student_religion"  required>
                        </div>
                        <div class="input_box">
                            <label for="">Birth Certificate or NID No <span style="color:red">*</span> </label>
                            <input type="text" name="student_nid"  required>
                        </div>
                        <div class="input_box">
                            <label for="">Present Address <span style="color:red">*</span> </label>
                            <input type="text" name="student_present_address"  required>
                        </div>
                        <div class="input_box">
                            <label for="">Permanent Address <span style="color:red">*</span> </label>
                            <input type="text" name="student_permanent_address"  required>
                        </div>
                        <div class="input_box">
                            <label for="">City <span style="color:red">*</span> </label>
                            <input type="text" name="student_city"  required>
                        </div>
                        <div class="input_box">
                            <label for="">State <span style="color:red">*</span> </label>
                            <input type="text" name="student_state"  required>
                        </div>
                        <div class="input_box">
                            <label for="">Student Image <span style="color:red">*</span> </label>
                            <div class="custom-file-input">
                                <label for="browse-button" class="custom-file-label">Choose file...</label>
                                <input style="width:100%;" type="file" name="student_image" id="file-input" class="hidden" required accept="image/*"/>
                                <button type="button" id="browse-button" class="custom-browse-button">Browse</button>
                            </div>
                        </div>
   
                    </div>

                    <!-- Student Parent Details Section -->
                    <h3>Previous Institute Details</h3>
                    <div class="student_previous_details_form input_main_box">
                        <div class="input_box">
                            <label for="">Institute Name <span style="color:red">*</span> </label>
                            <input type="text" name="student_previous_institute_name"  required>
                        </div>
                        <div class="input_box">
                            <label for="">Qualification <span style="color:red">*</span> </label>
                            <input type="text" name="student_previous_institute_qualification"  required>
                        </div>
                        <div class="input_box">
                            <label for="">Remarks <span style="color:red">*</span> </label>
                            <input type="text" name="student_previous_institute_remarks" required>
                        </div>
                    </div>
                    <h3>Student Parent Details</h3>
                    <div class="student_parent_form input_main_box">
                        <div class="input_box">
                            <label for="">Guardian Name <span style="color:red">*</span> </label>
                            <input type="text" name="student_parent_name" placeholder="Guardian Name" required>
                        </div>
                        <div class="input_box">
                            <label for="">Relation <span style="color:red">*</span> </label>
                            <input type="text" name="student_parent_relation" placeholder="Relation" required>
                        </div>
                        <div class="input_box">
                            <label for="">Father's Name <span style="color:red">*</span> </label>
                            <input type="text" name="student_father_name" placeholder="Father's Name" required>
                        </div>
                        <div class="input_box">
                        <label for="">Mother's Name <span style="color:red">*</span> </label>
                            <input type="text" name="student_mother_name" placeholder="Mother's Name" required>
                        </div>
                        <div class="input_box">
                            <label for="">Occupation <span style="color:red">*</span> </label>
                            <input type="text" name="student_parent_occupation" placeholder="Occupation" required>
                        </div>
                        <div class="input_box">
                            <label for="">Income <span style="color:red">*</span> </label>
                            <input type="text" name="student_parent_income" placeholder="Income" required>
                        </div>
                        <div class="input_box">
                            <label for="">Education <span style="color:red">*</span> </label>
                            <input type="text" name="student_parent_education" placeholder="Education" required>
                        </div>
                        <div class="input_box">
                            <label for="">Guardian Email <span style="color:red">*</span> </label>
                            <input type="text" name="student_parent_email" placeholder="Guardian Email" required>
                        </div>
                        <div class="input_box">
                            <label for="">Guardian Mobile No <span style="color:red">*</span> </label>
                            <input type="text" name="student_parent_number" placeholder="Guardian Mobile No" required>
                        </div>
                    </div>
                    <div class="text_area_box">
                        <label for="">Guardian Address <span style="color:red">*</span> </label>
                        <textarea type="text" name="student_parent_address" required></textarea>
                    </div>
                    <div class="input_main_box">
                        <div class="input_box">
                            <label for="">Guardian City <span style="color:red">*</span> </label>
                            <input type="text" name="student_parent_city"  required>
                        </div>
                        <div class="input_box">
                            <label for="">Guardian State <span style="color:red">*</span> </label>
                        <input type="text" name="student_parent_state"  required>
                        </div>
                        <div class="input_box">
                            <label for="">Guardian Photo <span style="color:red">*</span> </label>
                            
                            <div class="custom-file-input">
                                <label for="browse-button" class="custom-file-label">Choose file...</label>
                                <input style="width:100%;" type="file" name="student_parent_image" id="file-input" class="hidden" required accept="image/*"/>
                                <button type="button" id="browse-button" class="custom-browse-button">Browse</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <!-- Upload Documents Section -->
                   
                <h3 style="margin-top:40px">Upload Documents</h3>
                <label for="">Upload Documents <span style="color:red">*</span> </label>
                <div class="custom-file-input">
                    <label for="browse-button" class="custom-file-label">Choose file...</label>
                    <input style="width:100%;" type="file" name="student_documents" id="file-input" class="hidden" required accept="image/*"/>
                    <button type="button" id="browse-button" class="custom-browse-button">Browse</button>
                </div>
                </div>
                
                <div id="submit_btn_box">
                <input id="admission_submit_button" type="submit" name="add_student" value="Add Student">
                <?php wp_nonce_field('add_student', 'add_student_nonce'); ?>
                </div>

            </form>
        </div>
    </div>
    <style>
        #dashboard_admission_form_box form{
            padding:20px 20px;
            border: 1px solid #0000001f;
            border-radius: 4px;
            box-sizing: border-box;
        }
        form.admission_form_box {
            width: 100%;
            padding: 5px;
        }
        form.admission_form_box label {
            display: block;
            font-size: 15px;
            margin-bottom: 3px;
            color: #080808d9;
        }

        #dashboard_admission_form_box form input, select, textarea {
            width: 100%;
            padding: 7px 12px;
            border: 1px solid #0000002b;
            border-radius: 2px;
            font-size: 16px;
            font-weight: 400;
            outline: none !important;
            color: #080808d9;
        }
        #dashboard_admission_form_box form select , input[type="file"]{
            padding: 6px 12px;
        }
        .text_area_box{
            margin:20px 0px;
        }
        form.admission_form_box .input_main_box {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 25px;
        }
        @media screen and (max-width:992px){
            form.admission_form_box .input_main_box {
            grid-template-columns: 1fr 1fr;
        }
        }
        @media screen and (max-width:768px){
            form.admission_form_box .input_main_box {
            grid-template-columns: 1fr;
        }
        }
        form.admission_form_box h3 {
            font-size: 20px;
            font-weight: 600;
            color: #056839;
            margin-top:40px;
        }
        form.admission_form_box h3:nth-child(1) {
            margin-top: 0px;
        }


        /* Style the hidden file input */
        .custom-file-input input.hidden {
            display: none;
        }
        .custom-file-input {
            width: 100%;
            display: flex !important;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            margin: 0px;
            padding-left: 10px;
        }
        button#browse-button {
            width: 100px;
            display: inline;
            padding: 8px;
            font-size: 14px;
            border: none;
            outline: none !important;
        }
        .custom-file-input label {
            background: transparent;
            width: 100%;
            cursor:pointer;
            margin: 0px !important;
        }
        .custom-file-input {
            border: 1px solid #0000002e;
        }
        button#browse-button {
            width: 100px;
            display: inline;
            padding: 8px;
            font-size: 14px;
            border: none;
            outline: none !important;
            height: 100%;
        }
        .input_box {
            display: block !important;
        }
        input#admission_submit_button {
            display: block;
            width: auto !important;
            margin: auto;
            margin-top: 25px;
            background: #056839;
            color: #fff !important;
            padding: 7px 25px !important;
            border: 2px solid #056839;
            transition: all 0.5s;
        }
        input#admission_submit_button:hover{
            background: #fff;
            color: #056839 !important;
        }
    </style>
    <script>
        const fileInput = document.getElementById('file-input');
const browseButton = document.getElementById('browse-button');

browseButton.addEventListener('click', function () {
    fileInput.click();
});

fileInput.addEventListener('change', function () {
    const fileName = this.value.split('\\').pop(); // Get the selected file's name
    document.querySelector('.custom-file-label').textContent = fileName;
});

    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('admission_form', 'admission_form_shortcode');
?>
