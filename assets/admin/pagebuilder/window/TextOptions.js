import rangy from 'rangy/lib/rangy-core';
import 'rangy/lib/rangy-classapplier';
import 'rangy/lib/rangy-textrange';

export default class TextOptions {
    constructor() {
        this.savedRange = null;
    }

    init() {
        rangy.init();
        this.initButtons();
    }

    handleTextOptions(node) {
        const self = this;
        // Добавление контекстного меню при выделении
        node.querySelectorAll('[contenteditable="true"][data-text-options="true"]').forEach(editableElement => {
            editableElement.addEventListener('mouseup', (event) => {
                const selection = rangy.getSelection();
                if (selection.toString().length > 0) {
                    const {clientX: mouseX, clientY: mouseY} = event;
                    self.showContextMenu(mouseX, mouseY);
                    self.savedRange = selection.getRangeAt(0);
                }
            });
        });

        // Функция для отображения контекстного меню

        document.addEventListener('click', (event) => {
            if (window.getSelection().toString().length <= 0) {
                self.hideContextMenu();
            }
        });
    }

    initButtons() {
        const self = this;
        const boldApplier = rangy.createClassApplier('bold', {elementTagName: 'b'});
        const linkApplier = rangy.createClassApplier('url', {
            elementTagName: 'a',
            elementProperties: {href: ''}
        });

        document.getElementById('boldOption').addEventListener('click', () => {
            if (self.savedRange) {
                rangy.getSelection().setSingleRange(self.savedRange);
                boldApplier.toggleSelection();
                self.hideContextMenu();
            }
        });

        document.getElementById('linkOption').addEventListener('click', () => {
            if (self.savedRange) {
                const url = prompt('Enter the URL');
                if (url) {
                    rangy.getSelection().setSingleRange(self.savedRange);
                    linkApplier.elementProperties.href = url;
                    linkApplier.toggleSelection();
                    self.hideContextMenu();
                }
            }
        });
    }

    hideContextMenu() {
        const contextMenu = document.getElementById('textOptions');
        contextMenu.style.display = 'none';
    }

    showContextMenu() {
        const selection = rangy.getSelection().nativeSelection;

        if (selection && !selection.isCollapsed) {
            const range = selection.getRangeAt(0);
            const rect = range.getBoundingClientRect();
            const contextMenu = document.getElementById('textOptions');

            // Устанавливаем позицию контекстного меню над выделением
            contextMenu.style.position = 'fixed';
            contextMenu.style.top = `${rect.top - 50}px`;
            contextMenu.style.left = `${rect.left}px`;
            contextMenu.style.display = 'flex';
        }
    }
}



