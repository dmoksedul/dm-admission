<?php
// student list 
function display_student_list() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'dm_students';

    // Get the current page number from URL
    $current_page = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
    $per_page = 10; // Default number of items per page

    // Calculate the offset for the SQL query
    $offset = ($current_page - 1) * $per_page;

    // Retrieve all student data
    $all_students = $wpdb->get_results("SELECT * FROM $table_name");

    // Calculate the total number of students
    $total_students = count($all_students);

    // Calculate the total number of pages based on the total students and items per page
    $total_pages = ceil($total_students / $per_page);

    // Retrieve student data with pagination
    $students = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table_name LIMIT %d, %d", $offset, $per_page)
    );

    // Display the student list table
    echo '<div class="wrap">';
    echo '<h2>Student List</h2>';
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

    $list_number = 1; // Initialize list number

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