export default class ColorList {
    constructor(data, name) {
        this.data = data;
        this.name = name;
        this.id = data.id;
        this.node = document.getElementById(this.id);
        this.list = this.node.querySelector('.color-list');
        this.handleBlockEvents();
    }

    handleBlockEvents() {
        this.list.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault(); // предотвращаем стандартное поведение Enter
                this.insertNewListItem();
            }
        });
    }

    insertNewListItem(data = null) {
        const newListItem = document.createElement('li');
        newListItem.textContent += '- text';
        const newSpan = document.createElement('span');
        newSpan.textContent = 'Enter';

        if (data) {
            newSpan.textContent = data.caption;
            newListItem.textContent = data.text;
        }

        newListItem.prepend(newSpan);
        this.list.appendChild(newListItem);

        // Устанавливаем курсор внутрь нового span
        this.setCursorInElement(newSpan);
    }

    setCursorInElement(element) {
        const range = document.createRange();
        const sel = window.getSelection();
        range.setStart(element, 1);
        range.collapse(true);
        sel.removeAllRanges();
        sel.addRange(range);
        element.focus();
    }

    getData() {
        const data = [];
        const editableList = this.node.querySelector('.color-list');
        const listItems = editableList.querySelectorAll('li');

        if (!listItems) {
            return null;
        }

        listItems.forEach((item) => {
            const clone = item.cloneNode(true);

            const captionEl = clone.querySelector('span');
            const caption = captionEl.innerText;

            captionEl.remove();
            const text = clone.innerText;

            data.push({
                caption: caption,
                text: text
            })

            clone.remove();
        });

        return data;
    }

    setData(data) {
        this.list.innerHTML = '';

        for (const line of data) {
          this.insertNewListItem(line)
        }
    }
}