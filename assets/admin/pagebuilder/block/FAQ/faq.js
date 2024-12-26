import stringToNode from "../../components/htmlParser";
import './style.scss'
import ContextMenu from "../../window/ContextMenu";

export default class FAQ {
    constructor(data, name) {
        this.data = data;
        this.id = data.id;
        this.name = name;
        this.node = document.getElementById(this.id);
        this.contextMenu = new ContextMenu(this.node.querySelector('.pb__context-menu'));
        this.selectedItem = null;
        this.handleBlockEvents();
    }

    handleBlockEvents() {
        const addButton = document.getElementById(this.data.id).querySelector('[data-action="add-faq-item"]');
        addButton.addEventListener('click', () => this.addListItem());
        this.node.querySelector('[data-action="delete-item"]').addEventListener('click', () => this.handleItemDelete())
    }

    addListItem(data = null) {
        const prototype = stringToNode(this.data.prototype, false);
        prototype.querySelector('.accordion__item-header--button').addEventListener('click', () => this.handleItemCollapse(prototype))
        prototype.addEventListener('contextmenu', (event) => this.handleItemRightClick(event));

        if (data) {
            prototype.querySelector('.faq__question').innerText = data.question;
            prototype.querySelector('.accordion__item--content-wrapper p').innerText = data.answer;
        }

        this.node.querySelector('.accordion').append(prototype);
    }

    handleItemCollapse(item) {
        if (item.classList.contains('show')) {
            item.classList.remove('show');
            return;
        }

        item.classList.add('show');
    }

    handleItemRightClick(event) {
        if (event.target.classList.contains('accordion-item')) {
            this.selectedItem = event.target;
        } else {
            this.selectedItem = event.target.closest('.accordion__item');
        }

        this.contextMenu.open(event);
    }

    handleItemDelete() {
        if (this.selectedItem) {
            this.selectedItem.remove();
        }

        this.contextMenu.close();
    }

    getData() {
        const data = [];
        const faq = this.node.querySelector('.faq__items');
        const items = faq.querySelectorAll('.accordion__item');

        if (!items.length) {
            return null;
        }

        items.forEach((item) => {
            const question = item.querySelector('.faq__question').innerText;
            const answer = item.querySelector('.accordion__item--content-wrapper p').innerText;

            data.push({
                question: question,
                answer: answer
            })
        })

        return data;
    }

    setData(data) {
        for(const item of data){
            this.addListItem(item)
        }
    }
}