(function ($) {
    function readURL(input, previewEl) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(previewEl).attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $.fn.container = function () {
        return $(this).closest('.ui__thumb-upload');
    }

    $(document).ready(function () {
        let input = $('.thumb__input');

        $(document).on('change', '.thumb__input', function () {
            readURL(this, $(this).closest('.ui__thumb-upload').find('.thumb__img'));
            $(input).closest('.ui__thumb-upload').find('.thumb__placeholder').addClass('uploaded');
        });

        $('.thumb__placeholder').click(function () {
            $(this).closest('.ui__thumb-upload').find('.thumb__input').trigger('click');
        })
    })
})(jQuery);