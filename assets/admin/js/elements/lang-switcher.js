(function ($) {
    $(document).ready(function () {
       $('.toggle-lang').click(function () {
           const locale = $(this).attr('data-locale');

           $('.toggle-lang').removeClass('show');
           $('.toggle-lang[data-locale="'+locale+'"]').addClass('show');
       })
    })
})(jQuery);

