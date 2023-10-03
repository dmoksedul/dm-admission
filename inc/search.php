<?php
function student_search_shortcode() {
    ob_start();
    ?>
    <div class="student-search">
        <form id="dm_search_form" method="post">
            <div>
                <label for="student_name">Full Name:</label>
                <input type="text" name="student_name" id="student_name" required>
            </div>
            <div>
                <label for="student_birthdate">Birthday:</label>
                <input type="date" name="student_birthdate" id="student_birthdate" required>
            </div>
            <div>
                <input type="submit" name="search_students" value="Search">
            </div>
        </form>
        <div id="dm_result_box" class="search-results">
            <?php
            if (isset($_POST['search_students'])) {
                // Handle the search query and display results here
                $search_name = sanitize_text_field($_POST['student_name']);
                $search_birthdate = sanitize_text_field($_POST['student_birthdate']);

                global $wpdb;
                $table_name = $wpdb->prefix . 'dm_students';
                $students = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT * FROM $table_name WHERE CONCAT(student_first_name, ' ', student_last_name) = %s AND student_birthdate = %s",
                        $search_name,
                        $search_birthdate
                    )
                );
                if ($students) {
                    foreach ($students as $student) {
                        echo '<div id="dm_main_id_card_box">';
                        // front page
                        echo '<div id="dm_student_id_card_box" class="student-result">';
                        echo '<h3>Student ID Card</h3>';
                        // Display the image with a width of 800px
                        echo '<img src="' . esc_url(wp_get_attachment_image_url($student->student_image, 'custom_image_size')) . '" alt="' . esc_attr($student->student_first_name . ' ' . $student->student_last_name) . '">';
                        echo '<h3 class="dm_student_name">' . esc_html($student->student_first_name . ' ' . $student->student_last_name) . '</h3>';
                        echo '<div class="information_box"';
                        echo '<p>Class: ' . esc_html($student->class) . '</p>';
                        echo '<p>Blood: ' . esc_html($student->student_blood_group) . '</p>';
                        echo '<p>Phone: ' . esc_html($student->student_phone_number) . '</p>';
                        echo '<p>Email: ' . esc_html($student->student_email) . '</p>';
                        echo '<p>Location: ' . esc_html($student->student_city . ', ' . $student->student_state) . '</p>';
                        echo '</div>';
                        echo '</div>';
                        // back page
                        echo '<div id="dm_student_id_card_box" class="student-result dm_id_card_back_page">';
                        echo '<h3>Student ID Card</h3>';
                        // Display the image with a width of 800px
                        echo '<img src="' . esc_url(wp_get_attachment_image_url($student->student_image, 'custom_image_size')) . '" alt="' . esc_attr($student->student_first_name . ' ' . $student->student_last_name) . '">';
                        echo '<h3 class="dm_student_name">' . esc_html($student->student_first_name . ' ' . $student->student_last_name) . '</h3>';
                        echo '<div class="information_box"';
                        echo '<p>Class: ' . esc_html($student->class) . '</p>';
                        echo '<p>Blood: ' . esc_html($student->student_blood_group) . '</p>';
                        echo '<p>Phone: ' . esc_html($student->student_phone_number) . '</p>';
                        echo '<p>Email: ' . esc_html($student->student_email) . '</p>';
                        echo '<p>Location: ' . esc_html($student->student_city . ', ' . $student->student_state) . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        
                        echo '<button id="print_id_card" onclick="print_id_card()">Download Id Card</button>';
                    }
                } else {
                    echo '<div id="dm_error_message" >No matching students found.</div>';
                }
            }
            ?>
        </div>
    </div>
    <script>
        function print_id_card(){
            window.print();
        }
    </script>
    <?php
    return ob_get_clean();
}

add_shortcode('student_search', 'student_search_shortcode');

// Add a custom image size for 800px width
add_image_size('custom_image_size', 800, 9999);
?>
