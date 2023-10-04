<?php
// Export student list as CSV
function export_student_list_csv() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'dm_students';

    // Retrieve student data from the database
    $students = $wpdb->get_results("SELECT * FROM $table_name");

    // Define the CSV file name
    $csv_filename = 'dm_student_list.csv';

    // Create CSV content
    $csv_data = "Institute Name,Class,Section,Admission Date,Category,First Name,Last Name,Gender,Birthday,Blood Group,Phone Number,Email,Religion,National ID,Present Address,Permanent Address,City,State,Previous Institute Name,Previous Institute Qualification,Previous Institute Remarks,Parent Name,Parent Relation,Father Name,Mother Name,Parent Occupation,Parent Income,Parent Education,Parent Email,Parent Phone Number,Parent Address,Parent City,Parent State,student_session,student_id_number,student_registration_number,student_roll_number,student_exam,student_subject,student_result\n";

    foreach ($students as $student) {
        // Prepare student data for CSV
        $student_data = array(
            $student->institute_name,
            $student->class,
            $student->section,
            $student->admission_date,
            $student->category,
            $student->student_first_name,
            $student->student_last_name,
            $student->student_gender,
            $student->student_birthdate,
            $student->student_blood_group,
            $student->student_phone_number,
            $student->student_email,
            $student->student_religion,
            $student->student_nid,
            $student->student_present_address,
            $student->student_permanent_address,
            $student->student_city,
            $student->student_state,
            $student->student_previous_institute_name,
            $student->student_previous_institute_qualification,
            $student->student_previous_institute_remarks,
            $student->student_parent_name,
            $student->student_parent_relation,
            $student->student_father_name,
            $student->student_mother_name,
            $student->student_parent_occupation,
            $student->student_parent_income,
            $student->student_parent_education,
            $student->student_parent_email,
            $student->student_parent_number,
            $student->student_parent_address,
            $student->student_parent_city,
            $student->student_parent_state,
            $student->student_session,
            $student->student_id_number,
            $student->student_registration_number,
            $student->student_roll_number,
            $student->student_exam,
            $student->student_subject,
            $student->student_result,
        );

        // Escape and format data for CSV
        foreach ($student_data as $key => $value) {
            $student_data[$key] = '"' . str_replace('"', '""', $value) . '"';
        }

        $csv_data .= implode(',', $student_data) . "\n";
    }

    // Set appropriate headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $csv_filename . '"');
    header('Pragma: no-cache');

    // Output CSV data
    echo $csv_data;
    exit;
}

// Handle the CSV export button click
if (isset($_GET['page']) && $_GET['page'] === 'student-list' && isset($_GET['action']) && $_GET['action'] === 'export-csv') {
    export_student_list_csv();
}

?>