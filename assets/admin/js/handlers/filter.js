$('.filter__category').change(function (){
    const category = $(this).val();
    window.location = window.location.href = "?filter="+category;
})