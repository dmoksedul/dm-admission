<?php

// display pending admission
function display_pending_admission() {
    // Retrieve pending admission data from the session variable
    $pending_admissions = isset($_SESSION['pending_admissions']) ? $_SESSION['pending_admissions'] : array();

    echo '<div class="wrap">';
    echo '<h2>Pending Admission</h2>';

    // Pagination variables
    $current_page = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
    $per_page = 10; // Number of items per page
    $total_pending_admissions = count($pending_admissions);
    $total_pages = ceil($total_pending_admissions / $per_page);
    $offset = ($current_page - 1) * $per_page;

    $counter = 1; // Initialize the counter to 1

    if (!empty($pending_admissions)) {
        // Reverse the order of pending admissions
        $pending_admissions = array_reverse($pending_admissions);

        echo '<table id="student_list_table_box" class="wp-list-table widefat fixed striped">';
        echo '<thead><tr>';
        echo '<th style="width:30px">No</th>';
        echo '<th style="width:100px">Student Image</th>';
        echo '<th>Student Name</th>';
        echo '<th>Class</th>';
        echo '<th>Section</th>';
        echo '<th>Category</th>';
        echo '<th>Father Name</th>';
        echo '<th>Location</th>';
        echo '<th>Admission Date</th>';
        echo '<th style="width:200px">Actions</th>';
        echo '</tr></thead>';
        echo '<tbody>';

        // Display items based on pagination
        for ($i = $offset; $i < min($offset + $per_page, $total_pending_admissions); $i++) {
            $pending_admission = $pending_admissions[$i];
            $student_name = isset($pending_admission['student_first_name']) && isset($pending_admission['student_last_name']) ? $pending_admission['student_first_name'] . ' ' . $pending_admission['student_last_name'] : 'N/A';
            $father_name = isset($pending_admission['student_father_name']) ? $pending_admission['student_father_name'] : 'N/A';
            $class = isset($pending_admission['class']) ? $pending_admission['class'] : 'N/A';
            $section = isset($pending_admission['section']) ? $pending_admission['section'] : 'N/A';
            $category = isset($pending_admission['category']) ? $pending_admission['category'] : 'N/A';
            $admission_date = isset($pending_admission['admission_date']) ? $pending_admission['admission_date'] : 'N/A';
            $location = isset($pending_admission['student_city']) && isset($pending_admission['student_state']) ? $pending_admission['student_city'] . ', ' . $pending_admission['student_state'] : 'N/A';

            // Check if the 'student_image' key exists in the array
            $student_image_id = isset($pending_admission['student_image']) ? $pending_admission['student_image'] : null;

            echo '<tr>';
            echo '<td>' . ($offset + $counter) . '</td>'; // Display the counter
            echo '<td>';

            if ($student_image_id) {
                $student_image_url = wp_get_attachment_image_src($student_image_id, 'thumbnail');
                if ($student_image_url) {
                    echo '<img width="50" height="50" src="' . esc_url($student_image_url[0]) . '" alt="' . esc_attr($student_name) . '">';
                }
            }

            echo '</td>';
            echo '<td>' . esc_html($student_name) . '</td>';
            echo '<td>' . esc_html($class) . '</td>';
            echo '<td>' . esc_html($section) . '</td>';
            echo '<td>' . esc_html($category) . '</td>';
            echo '<td>' . esc_html($father_name) . '</td>';
            echo '<td>' . esc_html($location) . '</td>';
            echo '<td>' . esc_html($admission_date) . '</td>';
            echo '<td>';

            // Inside the foreach loop for pending admissions
            echo '<form method="post">';
            echo '<input type="hidden" name="approve_admission_index" value="' . $i . '">';
            // Serialize and encode the pending admission data as a hidden field
            echo '<input type="hidden" name="pending_admission_data" value="' . esc_attr(base64_encode(serialize($pending_admission))) . '">';
            echo '<div style="display:flex; flex-direction:row;justify-content:center;align-items:center;gap:20px; width:100%">';
            echo '<button type="submit" name="approve_admission" class="button">Approve</button>';
            echo '<button type="submit" name="delete_admission" class="button danger" onclick="return confirm(\'Are you sure you want to delete this admission?\')">Delete</button>';
            echo '</div>';
            echo '</form>';
            echo '</tr>';

            $counter++; // Increment the counter for the next item
        }

        echo '</tbody></table>';

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
    } else {
        echo '<p>No pending admissions.</p>';
    }
    echo '</div>';
}

function approve_admission_submission() {
    if (isset($_POST['approve_admission'])) {
        $index = intval($_POST['approve_admission_index']);

        // Retrieve the serialized pending admission data
        $serialized_data = isset($_POST['pending_admission_data']) ? $_POST['pending_admission_data'] : '';

        // Unserialize and decode the data
        $pending_admission = unserialize(base64_decode($serialized_data));

        global $wpdb;
        $table_name = $wpdb->prefix . 'dm_students';

        // Insert the data into the database table
        $wpdb->insert(
            $table_name,
            $pending_admission
        );

        // Remove the approved data from the session variable
        if (isset($_SESSION['pending_admissions'][$index])) {
            unset($_SESSION['pending_admissions'][$index]);
        }

        // Reindex the session array to remove gaps in the index
        $_SESSION['pending_admissions'] = array_values($_SESSION['pending_admissions']);
    }
}
add_action('init', 'approve_admission_submission');

function delete_admission_submission() {
    if (isset($_POST['delete_admission'])) {
        $index = intval($_POST['approve_admission_index']); // Change this line to use the correct index name 'delete_admission_index'

        // Remove the item from the session variable
        if (isset($_SESSION['pending_admissions'][$index])) {
            unset($_SESSION['pending_admissions'][$index]);

            // Reindex the session array to remove gaps in the index
            $_SESSION['pending_admissions'] = array_values($_SESSION['pending_admissions']);
        }
    }
}
add_action('init', 'delete_admission_submission');
?>
