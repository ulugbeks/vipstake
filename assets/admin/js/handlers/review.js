$('#add-review').submit(function (e) {
    e.preventDefault();
    const formData = new FormData($(this)[0])
    const form = $(this)[0];

    $.ajax({
        url: '/admin/writer/add-review', // Replace with your server-side endpoint
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            $(response).appendTo('.reviews-container');
            form.reset();
        },
        error: function (xhr, status, error) {
            // Handle errors
            console.error('Error uploading file:', error);
        }
    });
})

$(document).on('click', '.writer__review .delete', function () {
    const container = $(this).closest('.writer__review');
    const id = $(this).attr('data-id');
    $.ajax({
        url: '/admin/writer/delete-review/' + id, // Replace with your server-side endpoint
        type: 'GET',
        success: function (response) {
            console.log(response);
            $(container).remove();
        },
    })
})

$('.article__reviews [data-approve]').click(function () {
    const item = $(this).closest('.review__item');
    const id = $(this).attr('data-approve');

    $.get(`/admin/reviews/approve/${id}`, function () {
        $(item).addClass('approved');
    })
})

$('.article__reviews [data-remove]').click(function () {
    const item = $(this).closest('.review__item');
    const id = $(this).attr('data-remove');

    $.get(`/admin/reviews/remove/${id}`, function () {
        $(item).remove();
    })
})