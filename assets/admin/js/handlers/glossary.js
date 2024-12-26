$(document).ready(function () {
    $('#add-item').click(function (e) {
        e.preventDefault();
        $('.glossary-modal').css('display', 'flex');
    })

    $('.glossary-modal').click(function (e) {
        if(e.target.classList.contains('glossary-modal')){
            $('.glossary-modal').css('display', 'none');
        }
    })
})
