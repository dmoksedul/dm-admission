<?php
function display_pending_admission() {
    // Retrieve pending admission data from the session variable
    $pending_admissions = isset($_SESSION['pending_admissions']) ? $_SESSION['pending_admissions'] : array();

    echo '<div class="wrap">';
    echo '<h2>Pending Admission</h2>';

    $counter = 1; // Initialize the counter to 1

    if (!empty($pending_admissions)) {
        // Reverse the order of pending admissions
        $pending_admissions = array_reverse($pending_admissions);

        echo '<table id="student_list_table_box" class="wp-list-table widefat fixed striped">';
        echo '<thead><tr>';
        echo '<th>No</th>';
        echo '<th>Student Image</th>';
        echo '<th>Student Name</th>';
        echo '<th>Class</th>';
        echo '<th>Section</th>';
        echo '<th>Category</th>';
        echo '<th>Father Name</th>';
        echo '<th>Location</th>';
        echo '<th>Admission Date</th>';
        echo '<th>Actions</th>';
        echo '</tr></thead>';
        echo '<tbody>';

        foreach ($pending_admissions as $index => $pending_admission) {
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
            echo '<td>' . $counter . '</td>'; // Display the counter
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
            echo '<input type="hidden" name="approve_admission_index" value="' . $index . '">';
            // Serialize and encode the pending admission data as a hidden field
            echo '<input type="hidden" name="pending_admission_data" value="' . esc_attr(base64_encode(serialize($pending_admission))) . '">';
            echo '<button type="submit" name="approve_admission" class="button button-primary">Approve</button>';
            echo '<button type="submit" name="delete_admission" class="button button-secondary">Delete</button>';
            echo '</form>';
            echo '</tr>';

            $counter++; // Increment the counter for the next item
        }

        echo '</tbody></table>';
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
