jQuery(document).ready(function($) {
    // Add a click event handler for the "Edit" button
    $('.dm_student_item_edit_btn').on('click', function(e) {
        e.preventDefault();

        // Retrieve the student ID from the row (you can use data attributes)
        var studentID = $(this).data('student-id');

        // Perform an AJAX request to fetch the student data for editing
        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'fetch_student_data',
                student_id: studentID
            },
            success: function(response) {
                // Display a modal or popup with the student data for editing
                // You can use a library like Bootstrap Modal or create a custom popup.
                // Populate the form fields in the popup with the retrieved student data.
                if (response.success) {
                    var studentData = response.data;
                    // Populate the form fields in your popup using studentData
                    // Example:
                    $('#edit_student_modal input[name="student_first_name"]').val(studentData.student_first_name);
                    // Repeat this for other form fields
                    // Show the popup
                    $('#edit_student_modal').modal('show');
                }
            }
        });
    });

    // Add a submit event handler for the edit form
    $('#edit_student_form').on('submit', function(e) {
        e.preventDefault();

        // Collect the updated student data from the form
        var updatedData = {
            // Retrieve data from form fields
            student_first_name: $('#student_first_name').val(),
            // Repeat this for other form fields
        };

        // Perform an AJAX request to update the student data
        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'update_student_data',
                student_data: updatedData
            },
            success: function(response) {
                if (response.success) {
                    // Close the edit popup
                    $('#edit_student_modal').modal('hide');
                    // Display a success message to the user
                    alert(response.data.message);
                    // You can also update the table row with the new data if needed
                }
            }
        });
    });
});
