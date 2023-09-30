<?php
function student_search_shortcode() {
    ob_start();

    // Check if the form is submitted and process the search
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_name'])) {
        $search_term = sanitize_text_field($_POST['student_name']);

        // Query to search for students with matching names and retrieve additional details
        $args = array(
            'post_type' => 'students',
            'posts_per_page' => -1,
            's' => $search_term,
        );

        $student_query = new WP_Query($args);

        // Initialize a variable to check if matching students were found
        $matching_students_found = false;

        if ($student_query->have_posts()) {
            $matching_students_found = true;

            echo '<h2>Matching Students:</h2>';
            echo '<ul>';

            while ($student_query->have_posts()) {
                $student_query->the_post();
                $student_id = get_the_ID();
                $student_name = get_the_title();
                $student_class = get_post_meta($student_id, 'class', true);
                $father_name = get_post_meta($student_id, 'student_father_name', true);
                $mother_name = get_post_meta($student_id, 'student_mother_name', true);
                $dob = get_post_meta($student_id, 'student_birthdate', true);
                $student_photo = get_the_post_thumbnail($student_id, 'thumbnail');

                echo '<li>';
                echo '<div class="student-details">';
                if ($student_photo) {
                    echo '<div class="student-photo">' . $student_photo . '</div>';
                }
                echo '<div class="student-info">';
                echo '<p>Name: ' . $student_name . '</p>';
                echo '<p>Class: ' . $student_class . '</p>';
                echo '<p>Father\'s Name: ' . $father_name . '</p>';
                echo '<p>Mother\'s Name: ' . $mother_name . '</p>';
                echo '<p>Date of Birth: ' . $dob . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</li>';
            }

            echo '</ul>';
            wp_reset_postdata();
        } else {
            // No matching students found, set the message
            $message = '<p>No matching students found. Please try again.</p>';
            echo $message;
        }

        // Use JavaScript to toggle the student list visibility based on matching students
        echo '<script>';
        echo 'jQuery(document).ready(function($) {';
        echo 'var matchingStudentsFound = ' . ($matching_students_found ? 'true' : 'false') . ';';
        echo 'if (!matchingStudentsFound) {';
        echo '$(".student-list").hide();'; // Hide the student list initially
        echo '}';
        echo '});';
        echo '</script>';
    }

    // Display the search form
    ?>
    <form method="post" action="">
        <label for="student_name">Student Name:</label>
        <input type="text" name="student_name" id="student_name" />
        <input type="submit" value="Search" />
    </form>

    <!-- Display the student list with a class for JavaScript toggling -->
    <div class="student-list">
        <!-- This is where the matching student list will be displayed -->
    </div>
    <?php

    return ob_get_clean();
}
add_shortcode('student_search', 'student_search_shortcode');



?>