function expandButtonsOnHover(elements, width) {
    elements.forEach((element) => {
        element.addEventListener('mouseenter', () => {
            element.style.width = `${width}px`;
            element.classList.add('hover');
        });

        element.addEventListener('mouseleave', () => {
            element.style.width = '';
            element.classList.remove('hover');
        });
    });
}

const editButtons = document.querySelectorAll('.ui__listing-button');
const recoverButtons = document.querySelectorAll('.ui__button-recover');

if (editButtons) {
    expandButtonsOnHover(editButtons, 93);
}

if(recoverButtons){
    expandButtonsOnHover(recoverButtons, 139)
}
