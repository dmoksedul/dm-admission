<?php
// Function to display the search form and insert data
function display_search_results() {
    ?>
    <div class="wrap">
        <h1>Search Students by ID Number</h1>
        <form method="post" action="">
            <input type="text" name="student_id_number" placeholder="Student ID Number">
            <input type="submit" name="search_students" value="Search">
        </form>

        <?php
        if (isset($_POST['search_students'])) {
            // Handle the search query and insert data
            search_students_and_insert_data();
        }
        ?>
    </div>
    <?php
}

// Function to search students and insert data into the new database
function search_students_and_insert_data() {
    if (isset($_POST['student_id_number'])) {
        $student_id_number = sanitize_text_field($_POST['student_id_number']);

        global $wpdb;
        $source_table_name = $wpdb->prefix . 'dm_students'; // Your source table name
        $target_table_name = $wpdb->prefix . 'dm_students_esar'; // New database table

        $student = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT student_id, CONCAT(student_first_name, ' ', student_last_name) AS student_name, student_registration_number, student_phone_number FROM $source_table_name WHERE student_id_number = %s",
                $student_id_number
            )
        );

        if ($student) {
            // Insert data into the new database table
            $data = array(
                'student_id' => $student->student_id,
                'student_name' => $student->student_name,
                'student_registration_number' => $student->student_registration_number,
                'student_phone_number' => $student->student_phone_number,
            );

            $wpdb->insert($target_table_name, $data);

            echo '<p>Data inserted into the new database.</p>';
        } else {
            echo '<p>No students found with the provided ID number.</p>';
        }
    }
}

// Add a menu item for your plugin
function add_menu_item() {
    add_menu_page(
        'Student Search Plugin',
        'Student Search Plugin',
        'manage_options',
        'student-search-plugin',
        'display_search_form_and_insert_data'
    );
}

add_action('admin_menu', 'add_menu_item');
