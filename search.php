<?php
function student_search_shortcode() {
    ob_start();

    $matching_student_found = false; // Initialize the matching student flag
    $no_matching_students = false; // Initialize the no matching students flag

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['full_name']) && isset($_POST['birthdate'])) {
        $full_name = sanitize_text_field($_POST['full_name']);
        $birthdate = sanitize_text_field($_POST['birthdate']);

        $args = array(
            'post_type' => 'students',
            'posts_per_page' => -1,
            's' => $full_name, // Use the full name for the search
        );

        $student_query = new WP_Query($args);

        while ($student_query->have_posts()) {
            $student_query->the_post();
            $student_name = get_the_title();
            $student_id = get_the_ID();

            // Check if the student's name exactly matches the search term
            if (strcasecmp($student_name, $full_name) === 0) {
                // Check if the student's birthdate matches the input birthdate
                $student_birthdate = get_post_meta($student_id, 'student_birthdate', true);

                if ($student_birthdate === $birthdate) {
                    $matching_student_found = true;

                    // Display the student's information here
                    $student_class = get_post_meta($student_id, 'class', true);
                    $father_name = get_post_meta($student_id, 'student_father_name', true);
                    $student_phone = get_post_meta($student_id, 'student_phone', true);
                    $student_photo = get_the_post_thumbnail($student_id, 'thumbnail');

                    echo '<h4>Matching Student: ' . $student_name . '</h4>';
                    echo '<ul>';
                    echo '<li id="id_card_box">';
                    echo '<div class="student-details" data-student-id="' . $student_id . '">';

                    // Add JavaScript code to trigger the print dialog for this student information
                    echo '<script>';
                    echo 'window.onload = function() {';
                    echo '    printStudentInfo(' . $student_id . ');';
                    echo '};';
                    echo 'function printStudentInfo(studentId) {';
                    echo '    var studentInfo = document.querySelector(\'.student-details[data-student-id="\' + studentId + \'"]\').cloneNode(true);';
                    echo '    var printWindow = window.open(\'\', \'\', );'; // Adjusted width and height
                    echo '    var printContent = document.createElement("div");';
                    echo '    printContent.appendChild(studentInfo);';
                    echo '    printWindow.document.body.appendChild(printContent);';
                    echo '    printWindow.print();';
                    echo '    printWindow.close();';
                    echo '}';
                    echo '</script>';

                    // Rest of the student information display code (e.g., name, class, etc.)
                    echo '<div class="student-photo">' . $student_photo . '</div>';
                    echo '<h3 class="student_name">Name: ' . $student_name . '</h3>';
                    echo '<div class="student-info">';
                    echo '<p>Class: ' . $student_class . '</p>';
                    echo '<p>Father\'s Name: ' . $father_name . '</p>';
                    echo '<p>Phone: ' . $student_phone . '</p>';
                    echo '<p>Date of Birth: ' . $student_birthdate . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</li>';
                    echo '</ul>';
                    
                    // Add a download button for the ID card
                    echo '<form method="post" action="">
                    <input type="hidden" name="student_id" value="' . $student_id . '">
                    <input type="button" name="download_id_card" value="Download ID Card" onclick="printIdCard(' . $student_id . ')">
                    </form>';
                }
            }
        }

        wp_reset_postdata();

        if (!$matching_student_found) {
            $no_matching_students = true; // Set the flag if no matching students were found
        }
    }
    // Display an error message if no matching students were found
    if ($no_matching_students) {
        echo '<p>No student with this exact name and birthdate found.</p>';
    }
    // If no matching student found or if the search form should be displayed, show the search form
    if (!$matching_student_found || isset($_POST['show_search_form'])) {
        echo '<form id="search_form" method="post" action="">';
        echo '<div>';
        echo '<label for="full_name">Full Name:</label>';
        echo '<input type="text" name="full_name" id="full_name" placeholder="Full Name" required />';
        echo '</div>';
        echo '<div>';
        echo '<label for="birthdate">Birthdate:</label>';
        echo '<input type="date" name="birthdate" id="birthdate" required />';
        echo '</div>';
        echo '<input type="submit" value="Search" />';
        echo '</form>';
    }

    return ob_get_clean();
}

add_shortcode('student_search', 'student_search_shortcode');

// JavaScript function to print student information
?>
