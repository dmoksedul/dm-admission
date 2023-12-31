<?php



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

    // Get the current page number from URL
    $current_page = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
    $per_page = 10; // Number of items per page

    // Calculate the offset for the SQL query
    $offset = ($current_page - 1) * $per_page;

    // Query to retrieve trashed students
    $trashed_students = $wpdb->get_results(
        "SELECT * FROM $table_name WHERE trashed = 1 ORDER BY id DESC LIMIT $per_page OFFSET $offset"
    );

    // Calculate total number of trashed students
    $total_trashed_students = $wpdb->get_var(
        "SELECT COUNT(*) FROM $table_name WHERE trashed = 1"
    );

    // Calculate total number of pages based on total trashed students and items per page
    $total_pages = ceil($total_trashed_students / $per_page);

    // Display trashed students in a table
    echo '<div class="wrap">';
    echo '<h2>Trash Students</h2>';
    echo '<table id="student_list_table_box" class="wp-list-table widefat fixed">';
    echo '<thead><tr>';
    echo '<th style="width:30px">No</th>'; // Serial number column
    echo '<th>Image</th>';
    echo '<th>Name</th>';
    echo '<th>Student ID</th>';
    echo '<th>Birthday</th>';
    echo '<th>Phone Number</th>';
    echo '<th>Parent Name</th>';
    echo '<th>Location</th>';
    echo '<th style="width:200px">Actions</th>';
    echo '</tr></thead>';
    echo '<tbody>';

    if ($trashed_students) {
        $list_number = ($current_page - 1) * $per_page + 1; // Initialize list number

        foreach ($trashed_students as $student) {
            $student_name = $student->student_first_name . ' ' . $student->student_last_name;
            $location = $student->student_city . ', ' . $student->student_state;

            echo '<tr>';
            echo '<td>' . esc_html($list_number) . '</td>'; // Display the serial number
            echo '<td><img src="' . esc_url(wp_get_attachment_image_url($student->student_image, 'thumbnail')) . '" alt="' . esc_attr($student_name) . '" width="50"></td>'; // Display the student image
            echo '<td>' . esc_html($student_name) . '</td>';
            echo '<td>' . esc_html($student->student_id_number) . '</td>';
            echo '<td>' . esc_html(date('F j, Y', strtotime($student->student_birthdate))) . '</td>';
            echo '<td>' . esc_html($student->student_phone_number) . '</td>';
            echo '<td>' . esc_html($student->student_parent_name) . '</td>';
            echo '<td>' . esc_html($location) . '</td>';
            echo '<td>';
            echo '<div style="display:flex; flex-direction:row;justify-content:center;align-items:center;gap:20px; width:100%">';
            echo '<a href="?page=trash-students&action=restore&student_id=' . $student->id . '" class="button">Restore</a>';
            echo '<a href="javascript:void(0);" onclick="showCustomDeleteConfirmation(' . $student->id . ');" class="button danger">Delete</a>';

            echo '</div>';
            // Add this JavaScript code to show the custom confirmation dialog
    echo '
    <script>
    // Function to show a custom confirmation popup for the "Delete" action
    function showCustomDeleteConfirmation(studentId) {
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
        customDialog.innerHTML = "<p style=\"font-size:22px;font-weight:500\">Are you sure you want to delete this student permanently?</p>";
    
        // Add "Yes" and "No" buttons
        var yesButton = document.createElement("button");
        yesButton.className = "danger";
        yesButton.textContent = "Yes";
        yesButton.addEventListener("click", function() {
            // If "Yes" is clicked, redirect to the delete permanent action
            window.location.href = "?page=trash-students&action=delete_permanently&student_id=" + studentId;
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
    } else {
        echo '<tr><td colspan="9">No trashed students found.</td></tr>';
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
}
