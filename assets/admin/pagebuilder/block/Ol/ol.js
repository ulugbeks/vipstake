import './style.scss';

export default class Ol {
    constructor(data, name) {
        this.data = data;
        this.id = data.id;
        this.name = name;
        this.node = document.getElementById(this.id);
        this.handleBlockEvents();
    }

    handleBlockEvents() {

    }

    getData() {
        const data = [];
        const editableList = this.node.querySelector('.ordered');
        const listItems = editableList.querySelectorAll('li');

        if (!listItems) {
            return null;
        }

        listItems.forEach((item) => {
            const clone = item.cloneNode(true);
            data.push(clone.innerText)
            clone.remove();
        });

        return data;
    }

    setData(data) {
        const editableList = this.node.querySelector('.ordered');
        editableList.innerHTML = '';

        for (const line of data) {
            const node = document.createElement('li');
            node.innerHTML = line;
            editableList.append(node);
        }
    }
}