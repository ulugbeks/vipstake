const loader = "<img style='max-width: 80px; height: auto' class='image__loader' src='/admin/img/loader.svg'>";

(function ($) {
    $(document).ready(function () {
        let input = $('.image__input');

        $(document).on('change', '.image__input', function () {
            $(this).closest('.ui__image-upload').find('.thumb__placeholder').addClass('uploaded');
            let thumb = $(this).closest('.ui__image-upload').find('.thumb__img');
            let input = $(this).closest('.ui__image-upload').find('.image__path');
            uploadFile($(this)[0].files[0], thumb, input)
        });

        $(document).on('click', '.thumb__placeholder', function () {
            $(this).closest('.ui__image-upload').find('.image__input').trigger('click');
        })
    })

    function uploadFile(file, thumb, input){
        let formData = new FormData();
        formData.append('file', file);

        $.ajax({
            url: '/admin/ajax/image-upload',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $(thumb).attr('src', '/admin/img/loader.svg')
            },
            success: function(response) {
                $(thumb).attr('src', response.file.url)
                $(input).val(response.file.url);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $(thumb).attr('src', '')
                console.log('Error uploading file: ' + textStatus);
            }
        });
    }
})(jQuery);