<?php

// Display pending admission
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
        echo '<th style="width:250px">Actions</th>';
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
            // Add a "View" button with a link to the student details page
            $student_id = isset($pending_admission['id']) ? $pending_admission['id'] : 0;
            echo '<a href="' . admin_url('admin.php?page=pending-student-details&student_id=' . $student_id) . '" class="button">View</a>';
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



function display_pending_student_details_page() {
    if (isset($_GET['student_id'])) {
        $student_id = intval($_GET['student_id']);

        // Retrieve pending admission data from the session variable
        $pending_admissions = isset($_SESSION['pending_admissions']) ? $_SESSION['pending_admissions'] : array();

        // Check if the student ID is valid
        if ($student_id >= 0 && $student_id < count($pending_admissions)) {
            $student_data = $pending_admissions[$student_id];

            echo '<div class="wrap">';
            echo '<h2 style="text-align:center;margin:20px 10px">Pending Student Details</h2>';

            // Use a container div to control the layout
            echo '<div class="student-details-container">';

            echo '<div class="student_header_image_box">';
            // Display student image
            echo '<div class="student_image_box">';
            $student_image_id = isset($student_data['student_image']) ? $student_data['student_image'] : null;
            if ($student_image_id) {
                $student_image_url = wp_get_attachment_image_src($student_image_id, 'full');
                if ($student_image_url) {
                    echo '<img width="150" src="' . esc_url($student_image_url[0]) . '" alt="Student Image">';
                    echo '<h2>Student Image<h2/>';
                }
            } else {
                echo 'Image not available';
            }
            // echo '</div>';

            // // Display parent image
            // echo '<div class="student_image_box">';
            // $parent_image_id = isset($student_data['student_parent_image']) ? $student_data['student_parent_image'] : null;
            // if ($parent_image_id) {
            //     $parent_image_url = wp_get_attachment_image_src($parent_image_id, 'full');
            //     if ($parent_image_url) {
            //         echo '<img width="150" src="' . esc_url($parent_image_url[0]) . '" alt="Parent Image">';
            //         echo '<h2>Parent Image<h2/>';
            //     }
            // } else {
            //     echo 'Parent image not available';
            // }
            // echo '</div>';

            // // Display student documents
            // echo '<div class="pending_student_detail">';
            // $student_documents_id = isset($student_data['student_documents']) ? $student_data['student_documents'] : null;
            // if ($student_documents_id) {
            //     $student_documents_url = wp_get_attachment_url($student_documents_id);
            //     if ($student_documents_url) {
            //         echo '<a class="button" href="' . esc_url($student_documents_url) . '" target="_blank">View Documents</a>';
            //     }
            // } else {
            //     echo 'No documents available';
            // }
            echo '</div>';
            echo '</div>';

            // Display other student details
            $fields_to_display = array(
                'institute_name',
                'class',
                'section',
                'admission_date',
                'category',
                'subject_list',
                'student_first_name',
                'student_last_name',
                'student_gender',
                'student_birthdate',
                'student_blood_group',
                'student_phone_number',
                'student_email',
                'student_religion',
                'student_nid',
                'student_present_address',
                'student_permanent_address',
                'student_city',
                'student_state',
                'student_previous_institute_name',
                'student_previous_institute_qualification',
                'student_previous_institute_remarks',
                'student_parent_name',
                'student_parent_relation',
                'student_father_name',
                'student_mother_name',
                'student_parent_occupation',
                'student_parent_income',
                'student_parent_education',
                'student_parent_email',
                'student_parent_number',
                'student_parent_address',
                'student_parent_city',
                'student_parent_state',
                'student_session',
                'student_id_number'
            );
            echo '<div class="pending_student_detail_box">';

            foreach ($fields_to_display as $field) {
                
                echo '<div class="student-detail">';
                echo '<strong>' . ucwords(str_replace('_', ' ', $field)) . ':</strong>';
                $field_value = isset($student_data[$field]) ? $student_data[$field] : 'N/A';
                echo '<span>' . esc_html($field_value) . '</span>';
                echo '</div>';
            }

            echo '</div>'; // Close the student-details-container
            echo '</div>'; // Close the student-details-container

            echo '</div>'; // Close the wrap div
        } else {
            echo '<div class="wrap">';
            echo '<h2>Student Not Found</h2>';
            echo '<p>The requested student could not be found.</p>';
            echo '</div>';
        }
    } else {
        echo '<div class="wrap">';
        echo '<h2>Invalid Request</h2>';
        echo '<p>Invalid student ID.</p>';
        echo '</div>';
    }
}

