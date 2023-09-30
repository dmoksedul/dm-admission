<?php
/*
Plugin Name: Student Management Plugin
Description: A WordPress plugin for managing students.
Version: 1.0
Author: Your Name
*/

// Register a custom post type for Students
function add_dm_students_plugin_menu() {
    // Top-level menu item
    add_menu_page(
        'Students',
        'Students',
        'manage_options',
        'dm_students_plugin',
        'dm_students_page',
        'dashicons-businessman',
        6
    );

    // Submenu item for Admission
    add_submenu_page(
        'dm_students_plugin', // Parent slug
        'Admission', // Page title
        'Admission', // Menu title
        'manage_options',
        'dm_admission_submenu', // Menu slug
        'dm_admission_page' // Callback function for Admission submenu
    );
    
    // Submenu item for Pending Admission
    add_submenu_page(
        'dm_students_plugin', // Parent slug
        'Pending Admission', // Page title
        'Pending Admission', // Menu title
        'manage_options',
        'dm_pending_admission_submenu', // Menu slug
        'dm_pending_admission_page' // Callback function for Pending Admission submenu
    );
}
add_action('admin_menu', 'add_dm_students_plugin_menu');

// Include necessary files
include_once('admission_form.php');
include_once('student_list.php');
include_once('search.php');
include_once('student_admission.php');

// Create a custom post type for Pending Admissions
function create_pending_admission_post_type() {
    register_post_type('pending_admissions',
        array(
            'labels' => array(
                'name' => __('Pending Admissions'),
                'singular_name' => __('Pending Admission'),
            ),
            'public' => false, // Set to false to hide from the front end
            'show_ui' => true, // Set to true to show in the admin panel
        )
    );
}
add_action('init', 'create_pending_admission_post_type');


function dm_pending_admission_page() {
    // Query pending admission posts
    $args = array(
        'post_type' => 'pending_admissions',
        'posts_per_page' => -1,
        'post_status' => 'draft', // Filter by draft status
    );

    $pending_query = new WP_Query($args);

    if ($pending_query->have_posts()) :
        ?>
        <style>
            /* CSS styles for the table */
            table.pending-admissions-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            table.pending-admissions-table th,
            table.pending-admissions-table td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            table.pending-admissions-table th {
                background-color: #f2f2f2;
            }

            table.pending-admissions-table tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            table.pending-admissions-table tr:hover {
                background-color: #ddd;
            }

            table.pending-admissions-table img {
                max-width: 50px;
                height: auto;
            }

            .delete-button, .approve-button {
                background-color: #ff0000;
                color: #fff;
                border: none;
                padding: 5px 10px;
                cursor: pointer;
                margin-right: 5px;
            }
            .approve-button{
                background:#056839;
            }
            .delete-button:hover, .approve-button:hover {
                background-color: #cc0000;
            }
        </style>
        <div id="pending_admissions_list">
            <h2>Pending Admission Requests</h2>
            <table class="pending-admissions-table">
                <thead>
                    <tr>
                        <th>List Number</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Mobile Number</th>
                        <th>Image</th>
                        <th>Admission Date</th>
                        <th>Action</th> <!-- Added Action column for Delete and Approve buttons -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $list_number = 1;
                    while ($pending_query->have_posts()) : $pending_query->the_post();
                        $student_name = get_the_title();
                        $class = get_post_meta(get_the_ID(), 'class', true);
                        $mobile_number = get_post_meta(get_the_ID(), 'student_phone', true);
                        $admission_date = get_post_meta(get_the_ID(), 'admission_date', true);
                        $student_image = get_the_post_thumbnail(get_the_ID(), 'thumbnail');
                        $post_id = get_the_ID();
                        ?>
                        <tr>
                            <td><?php echo $list_number++; ?></td>
                            <td><?php echo $student_name; ?></td>
                            <td><?php echo $class; ?></td>
                            <td><?php echo $mobile_number; ?></td>
                            <td><?php echo $student_image; ?></td>
                            <td><?php echo $admission_date; ?></td>
                            <td>
                                <button class="delete-button" data-post-id="<?php echo $post_id; ?>">Delete</button>
                                <button class="approve-button" data-post-id="<?php echo $post_id; ?>">Approve</button>
                            </td>
                        </tr>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </tbody>
            </table>
        </div>
        <script>
            jQuery(document).ready(function ($) {
                // Attach a click event handler to the Delete buttons
                $(".delete-button").on("click", function () {
                    var postId = $(this).data("post-id");
                    var confirmation = confirm("Are you sure you want to permanently delete this item?");
                    if (confirmation) {
                        // Send an AJAX request to permanently delete the item
                        $.ajax({
                            type: "POST",
                            url: ajaxurl, // WordPress AJAX URL
                            data: {
                                action: "delete_admission",
                                post_id: postId,
                                security: "<?php echo wp_create_nonce('delete_admission_nonce'); ?>",
                            },
                            success: function (response) {
                                if (response === "success") {
                                    // Reload the page or update the list as needed
                                    location.reload();
                                } else {
                                    alert("Error deleting item.");
                                }
                            },
                        });
                    }
                });

                // Attach a click event handler to the Approve buttons
                $(".approve-button").on("click", function () {
                    var postId = $(this).data("post-id");
                    var confirmation = confirm("Are you sure you want to approve this admission?");
                    if (confirmation) {
                        // Send an AJAX request to change the post status to 'publish'
                        $.ajax({
                            type: "POST",
                            url: ajaxurl, // WordPress AJAX URL
                            data: {
                                action: "approve_admission",
                                post_id: postId,
                                security: "<?php echo wp_create_nonce('approve_admission_nonce'); ?>",
                            },
                            success: function (response) {
                                if (response === "success") {
                                    // Reload the page or update the list as needed
                                    location.reload();
                                } else {
                                    alert("Error approving admission.");
                                }
                            },
                        });
                    }
                });
            });
        </script>
        <?php
    else :
        echo 'No pending admission requests.';
    endif;
}



// Shortcode to display pending admissions
function display_pending_admissions_shortcode($atts) {
    ob_start();
    dm_pending_admission_page();
    return ob_get_clean();
}
add_shortcode('display_pending_admissions', 'display_pending_admissions_shortcode');

// Rest of your code for the admission form and other functionalities goes here...

?>
