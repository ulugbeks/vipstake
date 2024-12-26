import Sortable from 'sortablejs';

const groupPrototype = $('[data-group-prototype]').attr('data-group-prototype');
const fieldSet = document.querySelector('.fieldSet-collection');
const groupFields = ['Group', 'Repeater'];

if(fieldSet){
    Sortable.create(fieldSet, {
        swapThreshold: 0.10,
        group: "fields",
        onChange: function(/**Event*/evt) {
            updatePositions()
            const item = $(document).find(evt.item);
            $(item).find($('[name*="[parentLabel]"]')).val('');
        }
    })
}

$(document).ready(function () {
   let fields = document.querySelectorAll('.fieldSet');
   fields.forEach(function (field) {
       const container = field.querySelector('.children-container');

       if(container){
           Sortable.create(container, {
               group: "fields",

               onChange: function(/**Event*/evt) {
                   updatePositions()
                   const item = $(document).find(evt.item);
                   let value = $(item).parent().closest('.fieldSet').find($('[name*="[label]"]')).first().val();
                   $(item).find($('[name*="[parentLabel]"]')).val(value);
               }
           })
       }
   })

    $(document).on('change', '[name*="[type]"]', function () {
        let fieldSet = $(this).closest('.fieldSet');

        if (groupFields.includes($(this).find('option:selected').text())) {
            createGroupArea(fieldSet);
        } else{
            $(fieldSet).find('.children-container').hide();
        }
    })
})

function updatePositions(){
    let loopIndex = 1;
    $(document).find('.fieldSet').each(function () {
        $(this).find($('[name*="[position]"]')).val(loopIndex)
        loopIndex += 1;
    })
}

function createGroupArea(fieldSet){
    let childContainer = $(fieldSet).find('.children-container');
    if (!childContainer.length) {
        $(groupPrototype).insertBefore($(fieldSet).find('.remove-item'));
        Sortable.create($(fieldSet)[0].querySelector('.children-container'), {
            group: "fields",

            onChange: function(/**Event*/evt) {
                updatePositions()
                const item = $(document).find(evt.item);
                let value = $(item).parent().closest('.fieldSet').find($('[name*="[label]"]')).first().val();
                $(item).find($('[name*="[parentLabel]"]')).val(value);
            }
        })
    } else {
        $(childContainer).show();
    }
}