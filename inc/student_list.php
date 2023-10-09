<?php
global $wpdb;
$table_name = $wpdb->prefix . 'dm_students';


// Function to display the Student List
function display_student_list() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'dm_students';

    // Handle the "Trash" action if the URL parameter is set
    if (isset($_GET['action']) && $_GET['action'] === 'trash' && isset($_GET['student_id'])) {
        $student_id_to_trash = absint($_GET['student_id']);
        // Update the trashed column for the specific student record to mark as trashed (set to 1)
        $wpdb->update(
            $table_name,
            array('trashed' => 1), // Set trashed to 1 to mark as trashed
            array('id' => $student_id_to_trash),
            array('%d'), // Format for trashed column
            array('%d')  // Format for id column
        );
    }

    // Get the current page number from URL
    $current_page = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
    $per_page = 10; // Number of items per page

    // Initialize the search query
    $search_query = '';

    // Check if the search form is submitted
    if (isset($_POST['search_students'])) {
        $search_query = sanitize_text_field($_POST['student_search']);
        $current_page = 1; // Reset page number to 1 for search results
    }

    // Calculate the offset for the SQL query
    $offset = ($current_page - 1) * $per_page;

    // Modify your SQL query to search for students by name, student ID number, or student registration number and limit the results per page
    $students = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name 
            WHERE 
            (student_first_name LIKE '%%%s%%' OR student_last_name LIKE '%%%s%%' OR student_id_number LIKE '%%%s%%' OR student_registration_number LIKE '%%%s%%') 
            ORDER BY id DESC LIMIT %d, %d",
            $search_query,
            $search_query,
            $search_query,
            $search_query,
            $offset,
            $per_page
        )
    );

    // Check if students were successfully retrieved
    if ($students) {
        // Calculate the total number of students matching the search query
        $total_students = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM $table_name 
                WHERE 
                (student_first_name LIKE '%%%s%%' OR student_last_name LIKE '%%%s%%' OR student_id_number LIKE '%%%s%%' OR student_registration_number LIKE '%%%s%%')",
                $search_query,
                $search_query,
                $search_query,
                $search_query
            )
        );

        // Calculate the total number of pages based on the total students and items per page
        $total_pages = ceil($total_students / $per_page);

        // Display the student list table
        echo '<div class="wrap">';
        echo '<h2>Student List</h2>';
        echo '<div class="student_top_box">';
        // Search form
        echo '<form method="post">';
        echo '<input type="text" name="student_search" placeholder="Search by Name, Student ID, or Registration Number" required>';
        echo '<input type="submit" name="search_students" value="Search">';
        echo '</form>';
        // Add the export CSV button
        echo '<div style="display:flex;flex-direction:row; justify-content:center;align-items:center;gap:20px">';
        echo '<a type="button" href="?page=import-students" class="button">Import Students</a>';
        echo '<a type="button" href="?page=student-list&action=export-csv" class="button">Export Students</a>';
        echo '</div>';
        echo '</div>';
        echo '<table id="student_list_table_box" class="wp-list-table widefat fixed">';
        echo '<thead><tr>';
        echo '<th style="width:50px">No</th>';
        echo '<th style="width:90px">Image</th>';
        echo '<th>Name</th>';
        echo '<th>Student ID</th>'; // Add the column for Student ID Number
        echo '<th>Birthday</th>';
        echo '<th>Phone Number</th>';
        echo '<th>Parent Name</th>';
        echo '<th>Location</th>';
        echo '<th>Actions</th>';
        echo '</tr></thead>';
        echo '<tbody>';

        $list_number = ($current_page - 1) * $per_page + 1; // Initialize list number

        foreach ($students as $student) {
            // Get the student's first and last name
            $student_name = $student->student_first_name . ' ' . $student->student_last_name;

            // Get the student's location (city and state)
            $location = $student->student_city . ', ' . $student->student_state;

            echo '<tr>';
            echo '<td>' . esc_html($list_number) . '</td>';
            echo '<td><img src="' . esc_url(wp_get_attachment_image_url($student->student_image, 'thumbnail')) . '" alt="' . esc_attr($student_name) . '" width="50"></td>';
            echo '<td>' . esc_html($student_name) . '</td>';
            echo '<td>' . esc_html($student->student_id_number) . '</td>'; // Display the Student ID Number
            echo '<td>' . esc_html(date('F j, Y', strtotime($student->student_birthdate))) . '</td>';
            echo '<td>' . esc_html($student->student_phone_number) . '</td>';
            echo '<td>' . esc_html($student->student_parent_name) . '</td>';
            echo '<td>' . esc_html($location) . '</td>';
            echo '<td>';
            echo '<a href="?page=edit-student&student_id=' . $student->id . '" class="button">Edit</a>';
            echo '<a href="?page=dm_admission&action=trash&student_id=' . $student->id . '" class="button">Trash</a>';
            echo '</td>';

            echo '</tr>';

            $list_number++; // Increment list number
        }

        echo '</tbody>';
        echo '</table>';

        // Pagination
        echo '<div class="tablenav">';
        echo '<div id="pagination_box" class="tablenav-pages">';
        echo paginate_links(array(
            'base' => add_query_arg('paged', '%#%'),
            'format' => '',
            'prev_text' => '&laquo;',
            'next_text' => '&raquo;',
            'total' => $total_pages,
            'current' => $current_page,
        ));
        echo '</div>';
        echo '</div>';

        echo '</div>';
    } else {
        // No students found
        echo '<p>No students found.</p>';
    }
}



// Function to display the Trash page list and handle Trash actions
function display_trash_students_list() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'dm_students';

    // Handle the "Trash" action if the URL parameter is set
    if (isset($_GET['action']) && $_GET['action'] === 'trash' && isset($_GET['student_id'])) {
        $student_id_to_trash = absint($_GET['student_id']);
        // Update the trashed column for the specific student record to mark as trashed (set to 1)
        $wpdb->update(
            $table_name,
            array('trashed' => 1), // Set trashed to 1 to mark as trashed
            array('id' => $student_id_to_trash),
            array('%d'), // Format for trashed column
            array('%d')  // Format for id column
        );
    }

    // Handle the "Delete Permanently" action if the URL parameter is set
    if (isset($_GET['action']) && $_GET['action'] === 'delete_permanently' && isset($_GET['student_id'])) {
        $student_id_to_delete = absint($_GET['student_id']);
        // Delete the specific student record permanently
        $wpdb->delete(
            $table_name,
            array('id' => $student_id_to_delete),
            array('%d') // Format for id column
        );
    }

    // Handle the "Restore" action if the URL parameter is set
    if (isset($_GET['action']) && $_GET['action'] === 'restore' && isset($_GET['student_id'])) {
        $student_id_to_restore = absint($_GET['student_id']);
        // Update the trashed column for the specific student record to mark as not trashed (set to 0)
        $wpdb->update(
            $table_name,
            array('trashed' => 0), // Set trashed to 0 to mark as not trashed (restored)
            array('id' => $student_id_to_restore),
            array('%d'), // Format for trashed column
            array('%d')  // Format for id column
        );
    }

    // Query to retrieve trashed students
    $trashed_students = $wpdb->get_results(
        "SELECT * FROM $table_name WHERE trashed = 1 ORDER BY id DESC"
    );

    // Display trashed students in a table
    echo '<div class="wrap">';
    echo '<h2>Trash</h2>';
    echo '<table class="wp-list-table widefat fixed">';
    echo '<thead><tr>';
    echo '<th>No</th>';
    echo '<th>Name</th>';
    echo '<th>Student ID</th>';
    echo '<th>Birthday</th>';
    echo '<th>Phone Number</th>';
    echo '<th>Parent Name</th>';
    echo '<th>Location</th>';
    echo '<th>Actions</th>';
    echo '</tr></thead>';
    echo '<tbody>';

    if ($trashed_students) {
        foreach ($trashed_students as $student) {
            $student_name = $student->student_first_name . ' ' . $student->student_last_name;
            $location = $student->student_city . ', ' . $student->student_state;

            echo '<tr>';
            echo '<td>' . esc_html($student->id) . '</td>';
            echo '<td>' . esc_html($student_name) . '</td>';
            echo '<td>' . esc_html($student->student_id_number) . '</td>';
            echo '<td>' . esc_html(date('F j, Y', strtotime($student->student_birthdate))) . '</td>';
            echo '<td>' . esc_html($student->student_phone_number) . '</td>';
            echo '<td>' . esc_html($student->student_parent_name) . '</td>';
            echo '<td>' . esc_html($location) . '</td>';
            echo '<td>';
            echo '<a href="?page=trash-students&action=restore&student_id=' . $student->id . '">Restore</a>';
            echo '<a href="?page=trash-students&action=delete_permanently&student_id=' . $student->id . '">Delete Permanently</a>';
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="8">No trashed students found.</td></tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}

?>
