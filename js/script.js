
jQuery(document).ready(function($) {
    $('.approve-admission').on('click', function(e) {
        e.preventDefault();
        var postId = $(this).data('post-id');
        $.ajax({
            type: 'POST',
            url: ajaxurl, // WordPress AJAX URL
            data: {
                action: 'approve_admission',
                post_id: postId,
            },
            success: function(response) {
                // Handle success (e.g., remove the row from the table)
            },
        });
    });

    $('.reject-admission').on('click', function(e) {
        e.preventDefault();
        var postId = $(this).data('post-id');
        $.ajax({
            type: 'POST',
            url: ajaxurl, // WordPress AJAX URL
            data: {
                action: 'reject_admission',
                post_id: postId,
            },
            success: function(response) {
                // Handle success (e.g., remove the row from the table)
            },
        });
    });
});