<?php

function dm_student_search_form() {
    $plugin_dir_path = plugin_dir_path(__FILE__);
    $image_url = plugin_dir_url($plugin_dir_path) . 'assets/img/logo.png';
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
                $student = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT * FROM $table_name WHERE CONCAT(student_first_name, ' ', student_last_name) = %s AND student_birthdate = %s LIMIT 1",
                        $search_name,
                        $search_birthdate
                    )
                );

                if ($student) {
                    echo '<div id="dm_main_id_card_box">';
                    // front page
                    echo '<div id="dm_student_id_card_box" class="student-result">';
                    echo '<h3>Student ID Card</h3>';
                    // Display the image with a width of 800px
                    echo '<img src="' . esc_url(wp_get_attachment_image_url($student->student_image, 'student_id_card_logo_size')) . '" alt="' . esc_attr($student->student_first_name . ' ' . $student->student_last_name) . '">';
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
                    
                    echo '
                        <div>
                        <h3>Terms and Conditions</h3>
                            <p class="institute_description">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dignissimos repellendus blanditiis aut eaque itaque, laborum sequi molestias fuga aliquam at?</p>
                            <img src="' . esc_url($image_url) . '" alt="Custom Image" />
                            <h4 id="institute_id_card_title">DM School & College </h4>
                        </div>
                        <ul>
                            <li>Email: info@moksedul.dev</li>
                            <li>Website: www.moksedul.dev</li>
                            <li>Location: Hatibandha, lalmonirhat, <br/> Rangpur Bangladesh</li>
                        </ul>
                        
                    ';
                
                    echo '</div>';
                    
                    echo '</div>';

                    echo '<button id="print_id_card" onclick="print_id_card()">Download Id Card</button>';

                    // Stop processing after displaying the first result
                    return;
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

add_shortcode('dm_student_id_card', 'dm_student_search_form');

// Add a custom image size for 800px width
add_image_size('student_id_card_logo_size', 800, 9999);
?>
