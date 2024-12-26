const button = $('.seo-meta__controls .lang');
$(button).click(function () {
    const lang = $(this).attr('data-lang');
    const meta = $(this).attr('data-meta');

    $(this).parent().find('.lang').removeClass('active');
    $(this).addClass('active');

    $('[data-fields-id]').each(function () {
        if($(this).attr('data-fields-id') === meta){
            $(this).addClass('-hidden');

            if($(this).attr('data-lang') === lang){
                $(this).removeClass('-hidden');
            }
        }
    })
})