$(document).ready(function () {
    setInitialIndex();

    $('.field__repeater .add-item').on('click', function () {
        let container = $(this).closest('.field__repeater');
        let fields = $(container).find('.field__repeater-container');
        let index = 0;
        $(fields).find('.field__repeater-row').each(function () {
            index += 1;
        })
        let prototype = $(container).find('[data-prototype]').attr('data-prototype').replace(/__index__/g, index);
        fields.append(prototype);
    })
})

function setInitialIndex(){
    $('.field__repeater').each(function () {
        let index = 0;

        $(this).find('.field__repeater-row').each(function () {
            $(this).find('[name]').each(function () {
                $(this).attr('name', $(this).attr('name').replace(/__index__/g, index));
            })

            index++;
        })
    })
}

$(document).on('click', '.repeater__row-delete', function () {
    $(this).closest('.field__repeater-row').remove();
})