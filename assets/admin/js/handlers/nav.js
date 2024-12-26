import Sortable from 'sortablejs';

const addButton = $('.nav__entity-add');
const navEntityItemProto =
    '               <div class="nav-item__container" data-nav-id="__INDEX__">\n' +
    '                   <div class="nav-item">\n' +
    '                       <div class="input-row">\n' +
    '                           <label for="">Title</label>\n' +
    '                           <input type="text" value="__TITLE__" name="nav[__INDEX__][title]">\n' +
    '                       </div>\n' +
    '                       <input type="hidden" name="nav[__INDEX__][id]" value="__ID__">\n' +
    '                       <input type="hidden" name="nav[__INDEX__][index]" value="__INDEX__">\n' +
    '                       <input type="hidden" name="nav[__INDEX__][type]" value="__TYPE__">\n' +
    '                       <input type="hidden" name="nav[__INDEX__][parent]" value="">\n' +
    '                       <input type="hidden" name="nav[__INDEX__][position]" value="">\n' +
    '                   </div>\n' +
    '                   <div class="children-container"><div class="drop-zone"></div></div>\n' +
    '               </div>';

const navCustomItemProto =
    '               <div class="nav-item__container" data-nav-id="__INDEX__">\n' +
    '                   <div class="nav-item">\n' +
    '                       <div class="input-row">\n' +
    '                           <label for="">Title</label>\n' +
    '                           <input type="text" value="__TITLE__" name="nav[__INDEX__][title]">\n' +
    '                           <input type="text" value="__URL__" name="nav[__INDEX__][url]">\n' +
    '                       </div>\n' +
    '                       <input type="hidden" name="nav[__INDEX__][index]" value="__INDEX__">\n' +
    '                       <input type="hidden" name="nav[__INDEX__][type]" value="Custom">\n' +
    '                       <input type="hidden" name="nav[__INDEX__][parent]" value="">\n' +
    '                       <input type="hidden" name="nav[__INDEX__][position]" value="">\n' +
    '                   </div>\n' +
    '                   <div class="children-container"><div class="drop-zone"></div></div>\n' +
    '               </div>';

const dropArea = $('.nav-menu-edit .drop-area')[0]

if (dropArea) {
    Sortable.create(dropArea, {
        swapThreshold: 0.10,
        group: "nav",
        animation: 150,
        onChange: function (/**Event*/evt) {
            updatePositions()
            updateParents();
        }
    })
}

function addNewEntityItem(el, group) {
    let item = navEntityItemProto;
    item = item.replaceAll('__TITLE__', $(el).siblings('label').text())
        .replaceAll('__ID__', $(el).attr('value'))
        .replaceAll('__TYPE__', $(group).attr('data-nav-group'))
        .replaceAll('__INDEX__', $('.nav-item__container').length)

    $(item).appendTo('.nav__drop-area');
    updatePositions();
}

function addNewCustomItem(el, group) {
    let item = navCustomItemProto;
    item = item.replaceAll('__TITLE__', $(group).find('[name="custom-title"]').val())
    item = item.replaceAll('__URL__', $(group).find('[name="custom-url"]').val())
        .replaceAll('__INDEX__', $('.nav-item__container').length)

    $(item).appendTo('.nav__drop-area');
    updatePositions();
}

function initSortable() {
    let navItems = document.querySelectorAll('.nav-menu-edit .nav-item__container');
    navItems.forEach(function (item) {
        const container = item.querySelector('.children-container .drop-zone');

        if (container) {
            Sortable.create(container, {
                group: "nav",
                swapThreshold: 0.5,
                fallbackOnBody: true,
                animation: 150,
                invertSwap: true,

                onChange: function (/**Event*/evt) {
                    updatePositions()
                    updateParents();
                }
            })
        }
    })
}

function updateParent(item) {
    const parent = $(item).closest('.drop-zone').closest('.nav-item__container');
    if (parent.length) {
        let value = $(parent).attr('data-nav-id');
        $(item).find($('[name*="[parent]"]')).val(value);
    } else {
        $(item).find($('[name*="[parent]"]')).val('');
    }
}

function updateParents() {
    $('.nav-item__container').each(function () {
        updateParent($(this));
    })
}

function updatePositions() {
    let loopIndex = 1;
    $(document).find('.nav-item__container').each(function () {
        $(this).find($('[name*="[position]"]')).val(loopIndex)
        loopIndex += 1;
    })
}

$(addButton).click(function (e) {
    e.preventDefault();
    const group = $(this).closest('.nav__entity-group');

    if ($(this).hasClass('--custom')) {
        addNewCustomItem($(this), group);
        initSortable();
        $(group).find('[name="custom-url"]').val('');
        $(group).find('[name="custom-title"]').val('');
    } else {
        $(group).find('.checkbox:checked').each(function () {
            addNewEntityItem($(this), group);
            initSortable();
            $(this).prop('checked', false);
        });
    }
})

$('.nav-item__delete').click(function () {
    $(this).closest('.nav-item__container').remove();
})

updatePositions();
updateParents();
initSortable();
