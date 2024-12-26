import './style.scss';

export default class Ul {
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
        const editableList = this.node.querySelector('.unordered');
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
        const editableList = this.node.querySelector('.unordered');
        editableList.innerHTML = '';

        for (const line of data) {
            const node = document.createElement('li');
            node.innerHTML = line;
            editableList.append(node);
        }
    }
}