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
        echo '<form method="post" class="student_search_student_list">';
        echo '<input type="text" name="student_search" placeholder="Name, ID, or Registration No" required>';
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
        echo '<th style="width:30px">No</th>';
        echo '<th style="width:90px">Image</th>';
        echo '<th>Name</th>';
        echo '<th>Student ID</th>'; // Add the column for Student ID Number
        echo '<th>Birthday</th>';
        echo '<th>Phone Number</th>';
        echo '<th>Parent Name</th>';
        echo '<th>Location</th>';
        echo '<th style="width:150px">Actions</th>';
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
            echo '<div style="display:flex; flex-direction:row;justify-content:center;align-items:center;gap:20px; width:100%">';
            echo '<a href="?page=edit-student&student_id=' . $student->id . '" class="button">Edit</a>';
            // Modify your "Trash" button to include a JavaScript confirmation dialog
            echo '<a href="javascript:void(0);" onclick="showCustomConfirmation(' . $student->id . ');" class="button danger">Trash</a>';
            echo '
            <script>
            function showCustomConfirmation(studentId) {
                // Create a main parent div with a class name
                var mainParentDiv = document.createElement("div");
                mainParentDiv.className = "custom-confirm-wrapper";
            
                // Create a custom dialog box element
                var customDialog = document.createElement("div");
                customDialog.className = "dm_custom_confirm_box";
            
                // Create a div for the button group and apply a class name
                var buttonGroup = document.createElement("div");
                buttonGroup.className = "dm_popup_box_button_group";
            
                // Add dialog content
                customDialog.innerHTML = "<p style=\"font-size:22px;font-weight:500\">Are you sure move to trash?</p>";
            
                // Add "Yes" and "No" buttons
                var yesButton = document.createElement("button");
                yesButton.className = "danger";
                yesButton.textContent = "Yes";
                yesButton.addEventListener("click", function() {
                    // If "Yes" is clicked, redirect to the trash action
                    window.location.href = "?page=dm_admission&action=trash&student_id=" + studentId;
                    closeCustomConfirmation();
                });
                buttonGroup.appendChild(yesButton);
            
                var noButton = document.createElement("button");
                noButton.textContent = "No";
                noButton.addEventListener("click", function() {
                    // If "No" is clicked, close the custom dialog
                    closeCustomConfirmation();
                });
                buttonGroup.appendChild(noButton);
            
                // Append the button group to the custom dialog
                customDialog.appendChild(buttonGroup);
            
                // Append the custom dialog to the main parent div
                mainParentDiv.appendChild(customDialog);
            
                // Append the main parent div to the body
                document.body.appendChild(mainParentDiv);
            
                // Style the main parent div and custom dialog with CSS (you can define your styles)
                mainParentDiv.style.position = "fixed";
                mainParentDiv.style.top = "0";
                mainParentDiv.style.left = "0";
                mainParentDiv.style.width = "100%";
                mainParentDiv.style.height = "100%";
                mainParentDiv.style.backgroundColor = "rgba(0, 0, 0, 0.5)";
                mainParentDiv.style.display = "flex";
                mainParentDiv.style.justifyContent = "center";
                mainParentDiv.style.alignItems = "center";
                mainParentDiv.style.zIndex = "9999";
            
                customDialog.style.backgroundColor = "#fff";
                customDialog.style.padding = "20px";
                customDialog.style.border = "1px solid #ccc";
                customDialog.style.boxShadow = "0 2px 10px rgba(0, 0, 0, 0.2)";
            }
            
            function closeCustomConfirmation() {
                // Find and remove the main parent div element
                var mainParentDiv = document.querySelector(".custom-confirm-wrapper");
                if (mainParentDiv) {
                    mainParentDiv.remove();
                }
            }
            </script>
            ';
            
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
        echo '<div id="dm_popup_area"><div><p>No students found.</p><a type="button" href="?page=import-students">Add Student</a></div></div>';
    }
}


function enqueue_custom_script() {
    // Enqueue the custom JavaScript file
    wp_enqueue_script('custom-script', plugins_url('/js/script.js', __FILE__), array(), '1.0', true);
}

// Hook the function to the appropriate action
add_action('wp_enqueue_scripts', 'enqueue_custom_script');

