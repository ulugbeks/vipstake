import stringToNode from "../../components/htmlParser";
import './style.scss'

export default class LevelList {
    constructor(data, name) {
        this.data = data;
        this.name = name;
        this.id = data.id;
        this.node = document.getElementById(this.id);
        this.handleBlockEvents();
    }

    handleBlockEvents() {
        const addButton = document.getElementById(this.data.id).querySelector('[data-action="add-list-item"]');
        addButton.addEventListener('click', () => this.addListItem());
    }

    addListItem(data = null) {
        const prototype = stringToNode(this.data.prototype, false);
        if(data){
            prototype.querySelector('.caption span').innerText = data.caption;
            prototype.querySelector('.text').innerText = data.text;
        }
        this.node.querySelector('.level-list').append(prototype);
    }

    getData() {
        const data = [];
        const list = this.node.querySelector('.level-list');
        const listItems = list.querySelectorAll('li');

        if (!listItems.length) {
            return null;
        }

        listItems.forEach((item) => {
            const caption = item.querySelector('.caption').innerText;
            const text = item.querySelector('.text').innerText;

            data.push({
                caption: caption,
                text: text
            })
        })

        return data;
    }

    setData(data) {
        this.node.querySelector('.level-list').innerHTML = '';

        data.forEach((item) => {
            this.addListItem(item)
        })
    }
}