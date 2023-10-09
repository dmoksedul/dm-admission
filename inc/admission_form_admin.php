<?php
// Create the admission form
function admission_form_page() {
    if (isset($_POST['add_student'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'dm_students';

        // Check if the student ID number already exists in the database
        $student_id_number = sanitize_text_field($_POST['student_id_number']);
        $existing_student = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM $table_name WHERE student_id_number = %s", $student_id_number)
        );

        if ($existing_student) {
            // Student ID number already exists, display an error message
            echo '<div class="error"><p>Student ID number already exists. Please choose a different ID number.</p></div>';
        } else {
            // Handle file uploads
            $uploaded_student_image_id = 0;
            $uploaded_parent_image_id = 0;
            $uploaded_document_id = 0;

            if (isset($_FILES['student_image']) && $_FILES['student_image']['error'] === 0) {
                $uploaded_student_image_id = media_handle_upload('student_image', 0);
            }

            if (isset($_FILES['student_parent_image']) && $_FILES['student_parent_image']['error'] === 0) {
                $uploaded_parent_image_id = media_handle_upload('student_parent_image', 0);
            }

            if (isset($_FILES['student_documents']) && $_FILES['student_documents']['error'] === 0) {
                $uploaded_document_id = media_handle_upload('student_documents', 0);
            }

            // Check for errors in file uploads
            if (is_wp_error($uploaded_student_image_id)) {
                // Handle the error for student image upload, e.g., display an error message
            }

            if (is_wp_error($uploaded_parent_image_id)) {
                // Handle the error for parent image upload, e.g., display an error message
            }

            if (is_wp_error($uploaded_document_id)) {
                // Handle the error for document upload, e.g., display an error message
            }

           // Sanitize and validate form data here
        $data = array(
            'institute_name' => sanitize_text_field($_POST['institute_name']),
            'class' => sanitize_text_field($_POST['class']),
            'section' => sanitize_text_field($_POST['section']),
            'admission_date' => sanitize_text_field($_POST['admission_date']),
            'category' => sanitize_text_field($_POST['category']),
            'subject_list' => sanitize_text_field($_POST['subject_list']),
            'student_first_name' => sanitize_text_field($_POST['student_first_name']),
            'student_last_name' => sanitize_text_field($_POST['student_last_name']),
            'student_gender' => sanitize_text_field($_POST['student_gender']),
            'student_birthdate' => sanitize_text_field($_POST['student_birthdate']),
            'student_blood_group' => sanitize_text_field($_POST['student_blood_group']),
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
            'student_image' => $uploaded_student_image_id,
            'student_parent_image' => $uploaded_parent_image_id,
            'student_documents' => $uploaded_document_id,
            'student_session' => sanitize_text_field($_POST['student_session']), // Add student_session field
            'student_id_number' => sanitize_text_field($_POST['student_id_number']), // Add student_id_number field
        );

        $wpdb->insert($table_name, $data);

            // Add success message or redirection here if needed
        }
    }



    // Display the content of your plugin's admin page here
    echo '<div class="wrap">';
    ?>
        <form id="student_admission_form" method="post" enctype="multipart/form-data">
            <h2>Admission Form </h2>
            <div class="admission_form_box">
                <h3><i class="fas fa-school"></i> Institute Details:</h3>
                <div class="form_details_box insitute_box">
                    <div class="input_box">
                        <!-- Institute Name -->
                        <label for="institute_name">Institute Name:</label>
                        <input value="<?php print bloginfo( 'title' ) ?>" readonly type="text" name="institute_name" id="institute_name" required>
                    </div>
                    <div class="input_box">
                        <!-- Student ID No Name -->
                        <label for="institute_name">Student ID No:</label>
                        <input value="" readonly type="text" name="student_id_number" id="student_id_number" required>
                    </div>

                    <script>
                        // Initialize an array to store generated IDs
                        var generatedIDs = [];

                        function generateRandomID() {
                            if (generatedIDs.length === 1000000) {
                                alert("All possible 6-digit IDs have been generated.");
                                return;
                            }

                            var uniqueDigits;
                            do {
                                uniqueDigits = String(Math.floor(100000 + Math.random() * 900000)); // Generate a random 6-digit number
                            } while (generatedIDs.includes(uniqueDigits));

                            generatedIDs.push(uniqueDigits); // Store the generated ID
                            document.getElementById('student_id_number').value = uniqueDigits;
                        }

                        // Generate an ID when the page loads
                        window.addEventListener('load', generateRandomID);
                    </script>



                    <div class="input_box">
                        <!-- Class -->
                        <label for="class">Class:</label>
                        <select name="class" id="class" required>
                            <option disabled value="" selected>Select</option>
                            <option value="One">One</option>
                            <option value="Two">Two</option>
                            <option value="Three">Three</option>
                            <option value="Four">Four</option>
                            <option value="Five">Five</option>
                            <option value="Six">Six</option>
                            <option value="Seven">Seven</option>
                            <option value="Eight">Eight</option>
                            <option value="Nine">Nine</option>
                            <option value="SSC">SSC</option>
                            <option value="HSC 1st Year">HSC 1st Year</option>
                            <option value="HSC 2nd Year">HSC 2nd Year</option>
                            <option value="Degree">Degree</option>
                            <option value="Honours">Honours</option>
                            <option value="Masters">Masters</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>

                    <div class="input_box">
                        <!-- Section -->
                        <label for="section">Section:</label>
                        <select name="section" id="section" required>
                            <option value="" disabled value="" selected>Select</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>

                    <div class="input_box">
                        <!-- Admission Date -->
                        <label for="admission_date">Admission Date:</label>
                        <input value="<?php echo date('Y-m-d'); ?>" type="date" name="admission_date" id="admission_date" required>
                    </div>

                    <div class="input_box">
                        <!-- Category -->
                        <label for="category">Category:</label>
                        <select name="category" id="category" required>
                            <option value="" disabled value="" selected>Select</option>
                            <option value="General">General</option>
                            <option value="Science">Science</option>
                            <option value="Arts">Arts</option>
                            <option value="Commerce">Commerce</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>
                    <div class="input_box">
                        <!-- Subject List -->
                        <label for="subject_list">Subject:</label>
                        <input value="Bangla, Englissh, ICT" type="text" name="subject_list" id="subject_list" required>
                    </div>
                    <div class="input_box">
                        <!-- Category -->
                        <label for="student_session">Session:</label>
                        <select name="student_session" id="student_session" required>
                            <option value="" disabled value="" selected>Select</option>
                            <option value="2020-2021">2020-2021</option>
                            <option value="2021-2022">2021-2022</option>
                            <option value="2022-2023">2022-2023</option>
                            <option value="2023-2024">2023-2024</option>
                            <option value="2024-2025">2024-2025</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>
                </div>

                <!-- Student Details -->
                <h3><i class="fas fa-user-edit"></i> Student Details:</h3>
                <div class="form_details_box student_details_box">
                    <div class="input_box">
                        <!-- Student First Name -->
                        <label for="student_first_name">First Name:</label>
                        <input type="text" name="student_first_name" id="student_first_name" required>
                    </div>

                    <div class="input_box">
                        <!-- Student Last Name -->
                        <label for="student_last_name">Last Name:</label>
                        <input type="text" name="student_last_name" id="student_last_name" required>
                    </div>

                    <div class="input_box">
                        <!-- Student Gender -->
                        <label for="student_gender">Gender:</label>
                        <select name="student_gender" id="student_gender" required>
                            <option value="" disabled value="" selected>Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Others">Others</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>

                    <!-- Student Birthdate -->
                    <div class="input_box">
                        <label for="student_birthdate">Birthdate:</label>
                        <input type="date" name="student_birthdate" id="student_birthdate" required>
                    </div>

                    <!-- New Student Blood Group Field -->
                    <div class="input_box">
                        <label for="student_blood_group">Blood Group:</label>
                        <select id="student_blood_group" name="student_blood_group" required>
                            <option value="" disabled selected>Select</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>

                    <!-- Student Phone Number -->
                    <div class="input_box">
                        <label for="student_phone_number">Phone Number:</label>
                        <input type="tel" name="student_phone_number" id="student_phone_number" required>
                    </div>

                    <!-- Student Email -->
                    <div class="input_box">
                        <label for="student_email">Email:</label>
                        <input type="email" name="student_email" id="student_email" required>
                    </div>

                    <!-- Student Religion -->
                    <div class="input_box">
                        <label for="student_religion">Religion:</label>
                        <input type="text" name="student_religion" id="student_religion" required>
                    </div>

                    <!-- Student National ID -->
                    <div class="input_box">
                        <label for="student_nid">National ID:</label>
                        <input type="text" name="student_nid" id="student_nid" required>
                    </div>
                </div>

                <!-- Student Addresses -->
                <div class="address_box">
                    <!-- Student Present Address -->
                    <div class="input_box">
                        <label for="student_present_address">Present Address:</label>
                        <textarea name="student_present_address" id="student_present_address" rows="4" required></textarea>
                    </div>

                    <!-- Student Permanent Address -->
                    <div class="input_box">
                        <label for="student_permanent_address">Permanent Address:</label>
                        <textarea name="student_permanent_address" id="student_permanent_address" rows="4" required></textarea>
                    </div>
                </div>

                <!-- Student City, State, and Image Upload -->
                <div class="form_details_box student_details_box">
                    <!-- Student City -->
                    <div class="input_box">
                        <label for="student_city">City:</label>
                        <input type="text" name="student_city" id="student_city" required>
                    </div>

                    <!-- Student State -->
                    <div class="input_box">
                        <label for="student_state">State:</label>
                        <input type="text" name="student_state" id="student_state" required>
                    </div>

                    <!-- Upload Student Image -->
                    <div class="input_box">
                        <label for="student_image">Upload Student Image:</label>
                        <input type="file" name="student_image" id="student_image" accept="image/*">
                    </div>
                </div>

                <!-- Previous Institute Details -->
                <h3><i class="fas fa-history"></i> Previous Institute Details:</h3>
                <div class="form_details_box previous_institute_box">
                    <!-- Student Previous Institute Name -->
                    <div class="input_box">
                        <label for="student_previous_institute_name">Previous Institute Name:</label>
                        <input type="text" name="student_previous_institute_name" id="student_previous_institute_name" required>
                    </div>

                    <!-- Student Previous Institute Qualification -->
                    <div class="input_box">
                        <label for="student_previous_institute_qualification">Previous Institute Qualification:</label>
                        <input type="text" name="student_previous_institute_qualification" id="student_previous_institute_qualification" required>
                    </div>
                </div>

                <div class="previous_box_sub">
                    <!-- Student Previous Institute Remarks -->
                    <div class="input_box">
                        <label for="student_previous_institute_remarks">Previous Institute Remarks:</label>
                        <textarea name="student_previous_institute_remarks" id="student_previous_institute_remarks" rows="4" required></textarea>
                    </div>
                </div>

                <!-- Parent Details -->
                <h3><i class="fas fa-user-secret"></i> Parent Details:</h3>
                <div class="form_details_box parent_details_box">
                    <!-- Parent Name -->
                    <div class="input_box">
                        <label for="student_parent_name">Parent Name:</label>
                        <input type="text" name="student_parent_name" id="student_parent_name" required>
                    </div>

                    <!-- Parent Relation -->
                    <div class="input_box">
                        <label for="student_parent_relation">Parent Relation:</label>
                        <input type="text" name="student_parent_relation" id="student_parent_relation" required>
                    </div>

                    <!-- Parent Occupation -->
                    <div class="input_box">
                        <label for="student_parent_occupation">Occupation:</label>
                        <input type="text" name="student_parent_occupation" id="student_parent_occupation" required>
                    </div>

                    <!-- Father Name -->
                    <div class="input_box">
                        <label for="student_father_name">Father Name:</label>
                        <input type="text" name="student_father_name" id="student_father_name" required>
                    </div>

                    <!-- Mother Name -->
                    <div class="input_box">
                        <label for="student_mother_name">Mother Name:</label>
                        <input type="text" name="student_mother_name" id="student_mother_name" required>
                    </div>

                    <!-- Parent Income -->
                    <div class="input_box">
                        <label for="student_parent_income">Income:</label>
                        <input type="text" name="student_parent_income" id="student_parent_income" required>
                    </div>

                    <!-- Parent Education -->
                    <div class="input_box">
                        <label for="student_parent_education">Education:</label>
                        <input type="text" name="student_parent_education" id="student_parent_education" required>
                    </div>

                    <!-- Parent Email -->
                    <div class="input_box">
                        <label for="student_parent_email">Parent Email:</label>
                        <input type="email" name="student_parent_email" id="student_parent_email" required>
                    </div>

                    <!-- Parent Phone Number -->
                    <div class="input_box">
                        <label for="student_parent_number">Parent Phone Number:</label>
                        <input type="tel" name="student_parent_number" id="student_parent_number" required>
                    </div>
                </div>

                <div class="sub_parent_box">
                    <!-- Parent Address -->
                    <div class="input_box">
                        <label for="student_parent_address">Parent Address:</label>
                        <textarea name="student_parent_address" id="student_parent_address" rows="4" required></textarea>
                    </div>
                </div>

                <div class="form_details_box parent_details_box_sub">
                    <!-- Parent City -->
                    <div class="input_box">
                        <label for="student_parent_city">Parent City:</label>
                        <input type="text" name="student_parent_city" id="student_parent_city" required>
                    </div>

                    <!-- Parent State -->
                    <div class="input_box">
                        <label for="student_parent_state">Parent State:</label>
                        <input type="text" name="student_parent_state" id="student_parent_state" required>
                    </div>
                    
                </div>
                <div class="parent_img_sub_box">
                    <!-- Upload Parent Image -->
                    <div class="input_box">
                        <label for="student_parent_image">Upload Parent Image:</label>
                        <input type="file" name="student_parent_image" id="student_parent_image" accept="image/*">
                    </div>
                </div>
                <!-- Upload Document -->
                <h3><i class="fas fa-file-alt"></i> Upload Document:</h3>
                <div class="document_box">
                    <!-- Upload Student Documents -->
                    <div class="input_box">
                        <label for="student_documents">Upload Student Documents:</label>
                        <input type="file" name="student_documents" id="student_documents" accept=".pdf,.doc,.docx">
                    </div>
                </div>

                <div class="submit_button_box">
                    <input type="submit" name="add_student" value="Add Student">
                </div>
            </div>
        </form>
    <style>
        input[type="file"] {
        position: relative;
        padding: 9px 12px !important;
        }

        input::file-selector-button {
        font-weight: bold;
        color: #000000cf;
        background: #10101012;
        border: none;
        border-radius: 0px;
        position: absolute;
        right:-5px;
        font-size:14px;
        font-weight:300;
        height:100%;
        top:0px;
        padding:0px 15px;
        cursor:pointer;
        }

        #student_admission_form{
            max-width:1200px;
            margin:auto;
            box-shadow: 0 0 3px #00000026;
            border-radius: 12px;
            border: 1px solid #0000001c;
            padding: 20px;
            box-sizing: border-box;
            background: #ffffff;
        }
        .form_details_box {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 25px;
        }
        .address_box, .previous_institute_box, .parent_details_box_sub{
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin:20px 0px
        }
        @media screen and (max-width: 1024px) {
            .form_details_box {
            grid-template-columns: 1fr 1fr;
        }
        }
        @media screen and (max-width: 678px) {
            .form_details_box {
            grid-template-columns: 1fr;
        }
        }
        .previous_box_sub, .sub_parent_box, .parent_img_sub_box {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin:20px 0px;
        }
        .admission_form_box input, select, textarea {
            display: block;
            width: 100%;
            border: 1px solid #0000001f;
            border-radius: 1px;
            font-size: 16px;
            padding: 3px 12px;
            outline:none !important;
        }
        .admission_form_box select {
            padding: 2.5px 10px;
            display: block;
            width: 100%;
            border: 1px solid #0000001f;
            border-radius: 1px;
            font-size: 16px;
        }
        .admission_form_box label {
            font-size: 16px;
            margin: 5px 5px;
            display: block;
            margin-top:0px;
        }
        .admission_form_box h3 {
            font-size: 20px;
            color: #056839;
            font-weight: 600;
            margin-top: 40px;
            margin-left:5px;
            line-height:22px;
        }
        .submit_button_box {
            margin-top: 25px;
        }
        .submit_button_box input[type="submit"] {
            display: block;
            width: auto;
            margin: auto;
            cursor: pointer;
        }
        
    </style>
    <?php
    echo '</div>';
}


?>