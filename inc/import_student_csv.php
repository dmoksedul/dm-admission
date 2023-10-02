<?php
// Function to display the import form
function import_students_form() {
    echo '<div class="wrap">';
    echo '<h2>Import Students from CSV</h2>';
    ?>
    <form method="post" enctype="multipart/form-data">
        <label for="csv_file">Select CSV File:</label>
        <input type="file" name="csv_file" id="csv_file" accept=".csv">
        <input type="submit" name="import_students" value="Import Students">
    </form>
    <?php
    echo '</div>';
}

// Function to parse CSV and insert data
function parse_csv_and_insert_data($csv_file) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'dm_students';

    $handle = fopen($csv_file, 'r');

    if ($handle !== false) {
        // Skip the header row if present
        fgetcsv($handle);

        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            // Process and sanitize data from the CSV row
            $institute_name = sanitize_text_field($data[0]);
            $class = sanitize_text_field($data[1]);
            $section = sanitize_text_field($data[2]);
            $admission_date = sanitize_text_field($data[3]);
            $category = sanitize_text_field($data[4]);
            $student_first_name = sanitize_text_field($data[5]);
            $student_last_name = sanitize_text_field($data[6]);
            $student_gender = sanitize_text_field($data[7]);
            $student_birthdate = sanitize_text_field($data[8]);
            $student_phone_number = sanitize_text_field($data[9]);
            $student_email = sanitize_email($data[10]);
            $student_religion = sanitize_text_field($data[11]);
            $student_nid = sanitize_text_field($data[12]);
            $student_present_address = sanitize_textarea_field($data[13]);
            $student_permanent_address = sanitize_textarea_field($data[14]);
            $student_city = sanitize_text_field($data[15]);
            $student_state = sanitize_text_field($data[16]);
            $student_previous_institute_name = sanitize_text_field($data[17]);
            $student_previous_institute_qualification = sanitize_text_field($data[18]);
            $student_previous_institute_remarks = sanitize_textarea_field($data[19]);
            $student_parent_name = sanitize_text_field($data[20]);
            $student_parent_relation = sanitize_text_field($data[21]);
            $student_father_name = sanitize_text_field($data[22]);
            $student_mother_name = sanitize_text_field($data[23]);
            $student_parent_occupation = sanitize_text_field($data[24]);
            $student_parent_income = sanitize_text_field($data[25]);
            $student_parent_education = sanitize_text_field($data[26]);
            $student_parent_email = sanitize_email($data[27]);
            $student_parent_number = sanitize_text_field($data[28]);
            $student_parent_address = sanitize_textarea_field($data[29]);
            $student_parent_city = sanitize_text_field($data[30]);
            $student_parent_state = sanitize_text_field($data[31]);

            // Insert data into the database
            $wpdb->insert(
                $table_name,
                array(
                    'institute_name' => $institute_name,
                    'class' => $class,
                    'section' => $section,
                    'admission_date' => $admission_date,
                    'category' => $category,
                    'student_first_name' => $student_first_name,
                    'student_last_name' => $student_last_name,
                    'student_gender' => $student_gender,
                    'student_birthdate' => $student_birthdate,
                    'student_phone_number' => $student_phone_number,
                    'student_email' => $student_email,
                    'student_religion' => $student_religion,
                    'student_nid' => $student_nid,
                    'student_present_address' => $student_present_address,
                    'student_permanent_address' => $student_permanent_address,
                    'student_city' => $student_city,
                    'student_state' => $student_state,
                    'student_previous_institute_name' => $student_previous_institute_name,
                    'student_previous_institute_qualification' => $student_previous_institute_qualification,
                    'student_previous_institute_remarks' => $student_previous_institute_remarks,
                    'student_parent_name' => $student_parent_name,
                    'student_parent_relation' => $student_parent_relation,
                    'student_father_name' => $student_father_name,
                    'student_mother_name' => $student_mother_name,
                    'student_parent_occupation' => $student_parent_occupation,
                    'student_parent_income' => $student_parent_income,
                    'student_parent_education' => $student_parent_education,
                    'student_parent_email' => $student_parent_email,
                    'student_parent_number' => $student_parent_number,
                    'student_parent_address' => $student_parent_address,
                    'student_parent_city' => $student_parent_city,
                    'student_parent_state' => $student_parent_state,
                )
            );
        }

        fclose($handle);
    }
}

add_action('admin_init', 'handle_csv_import');

function handle_csv_import() {
    if (isset($_POST['import_students'])) {
        if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === 0) {
            $csv_file = $_FILES['csv_file']['tmp_name'];
            parse_csv_and_insert_data($csv_file);
            wp_redirect(admin_url('admin.php?page=student-list'));
            exit();
        }
    }
}



?>