
$(document).ready(function () {

    $('.deleteBtn').on('click', function () {
        let removeUrl = $(this).attr('data-remove-url');
        $('.remove_item').attr('data-remove-url', removeUrl);
    });

    $(".remove_item").click(function () {
        let removeUrl = $(this).attr('data-remove-url');
        $.ajax({
            url: removeUrl,
            type: 'POST',
            data: {},
            contentType: 'text',
            success: function(data)
            {
                $('div.modal-content').html(data)
            },
            error: function(jqXHR){
                $('div.modal-content').html(jqXHR.responseText)
            }
        });
    });
});
