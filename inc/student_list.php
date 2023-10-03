<?php
// student list 
function display_student_list() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'dm_students';

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

    // Modify your SQL query to search for students by name and limit the results per page
    $students = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE student_first_name LIKE '%%%s%%' OR student_last_name LIKE '%%%s%%' ORDER BY id DESC LIMIT %d, %d",
            $search_query,
            $search_query,
            $offset,
            $per_page
        )
    );

    // Calculate the total number of students matching the search query
    $total_students = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE student_first_name LIKE '%%%s%%' OR student_last_name LIKE '%%%s%%'",
            $search_query,
            $search_query
        )
    );

    // Calculate the total number of pages based on the total students and items per page
    $total_pages = ceil($total_students / $per_page);

    // Display the student list table
    echo '<div class="wrap">';
    echo '<h2>Student List</h2>';
    // Add the export CSV button
    echo '<a href="?page=student-list&action=export-csv" class="button">Export CSV</a>';
    // Search form
    echo '<form method="post">';
    echo '<input type="text" name="student_search" placeholder="Search by student name">';
    echo '<input type="submit" name="search_students" value="Search">';
    echo '</form>';
    echo '<table class="wp-list-table widefat fixed">';
    echo '<thead><tr>';
    echo '<th style="width:50px">No</th>';
    echo '<th style="width:90px">Image</th>';
    echo '<th>Name</th>';
    echo '<th>Birthday</th>';
    echo '<th>Phone Number</th>';
    echo '<th>Parent Name</th>';
    echo '<th>Location</th>';
    echo '<th>Actions</th>'; // Add a new column for actions
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
        echo '<td>' . esc_html(date('F j, Y', strtotime($student->student_birthdate))) . '</td>';
        echo '<td>' . esc_html($student->student_phone_number) . '</td>';
        echo '<td>' . esc_html($student->student_parent_name) . '</td>';
        echo '<td>' . esc_html($location) . '</td>';
        echo '<td>';
        echo '<a href="?page=edit-student&student_id=' . $student->id . '" class="button">Edit</a>'; // Edit link
        echo '</td>';

        echo '</tr>';

        $list_number++; // Increment list number
    }

    echo '</tbody>';
    echo '</table>';

    // Pagination
    echo '<div class="tablenav">';
    echo '<div class="tablenav-pages">';
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
}

?>
