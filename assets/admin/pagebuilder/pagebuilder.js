import stringToNode from "./components/htmlParser";
import HttpClient from "./components/httpClient";
import LayoutWindow from "./window/LayoutWindow";
import BlocksWindow from "./window/BlocksWindow";
import TextOptions from "./window/TextOptions";
import DataCollector from "./components/dataCollector";


export default class PageBuilder {
    constructor(blocks) {
        this.contentContainer = document.getElementById('pb-content');
        this.bottomLayoutButton = document.getElementById('pb-bottom-add');
        this.layoutButtons = document.querySelectorAll('#pb-layout-add .pb__layout-button');
        this.blockButtons = document.querySelectorAll('#pb-block-add .pb__layout-button');
        this.layoutWindow = new LayoutWindow();
        this.blockWindow = new BlocksWindow();
        this.currentCol = null;
        this.content = [];
        this.registeredBlocks = blocks;
        this.textOptions = new TextOptions();
        this.textOptions.init();
        this.selectedBlock = null;
        this.activeLayout = null;
        this.blocks = {};

        this.init();
    }

    init() {
        document.addEventListener('click', (event) => {
            document.getElementById('blockTools').style.display = 'none';
            document.getElementById('layoutTools').style.display = 'none';
        })
        this.bottomLayoutButton.addEventListener('click', () => this.addLayoutContainer())
        this.layoutButtons.forEach((el) => {
            const layoutName = el.getAttribute('data-name')
            el.addEventListener('click', () => this.addLayout(layoutName))
        })

        this.blockButtons.forEach((el) => {
            const buttonName = el.getAttribute('data-name')
            el.addEventListener('click', () => this.addBlock(buttonName, this.currentCol))
        })
        document.getElementById('pb-save').addEventListener('click', () => this.save());

        this.initBlockToolbar();
        this.initLayoutToolbar();

        if (pageData.length) {
            this.loadData();
        }
    }

    initBlockToolbar() {
        document.getElementById('blockUp').addEventListener('click', () => this.blockUp())
        document.getElementById('blockDown').addEventListener('click', () => this.blockDown())
        document.getElementById('blockDelete').addEventListener('click', () => this.blockDelete())
    }

    initLayoutToolbar() {
        document.getElementById('layoutUp').addEventListener('click', () => this.layoutUp())
        document.getElementById('layoutDown').addEventListener('click', () => this.layoutDown())
        document.getElementById('layoutDelete').addEventListener('click', () => this.layoutDelete())
    }

    addLayoutContainer() {
        this.layoutWindow.open();
    }

    async addLayout(layoutName, layoutData = null) {
        const client = new HttpClient();
        const data = await client.get('/pagebuilder/layout?layout=' + layoutName)
        const layoutNode = stringToNode(data.template, false);
        layoutNode.setAttribute('data-layout', layoutName);
        this.contentContainer.append(layoutNode);
        this.layoutWindow.close();
        this.addBlockButtonEventHandler();
        this.registerLayout(data.id);
        document.addEventListener('click', (event) => this.handleLayoutFocus(event, layoutNode));
        layoutNode.click();

        if (layoutData) {
            this.processLayoutData(layoutNode, layoutData);
        }
    }

    async addBlock(blockName, col, blockData = null) {
        const client = new HttpClient();
        const data = await client.get('/pagebuilder/block?name=' + blockName);
        this.renderBlock(data, col);
        const block = new this.registeredBlocks[blockName](data.block, blockName);
        if (blockData) {
            block.setData(blockData);
        }
        this.blocks[block.id] = block;
        this.blockWindow.close();
    }

    renderBlock(data, col) {
        const node = stringToNode(data.template, false);
        node.classList.add('pb__block');
        const content = col.querySelector('.col-content');
        this.textOptions.handleTextOptions(node);
        content.append(node);
        document.addEventListener('click', (event) => this.handleFocus(event, node));
        node.click();
    }

    addBlockButtonEventHandler() {
        document.querySelectorAll('[data-action="block-add"]').forEach((button) => {
            button.addEventListener('click', () => this.openBlockWindow(button));
        })
    }

    openBlockWindow(button) {
        const colId = button.closest('.col').getAttribute('id');
        const layoutId = button.closest('.pb__layout-container').getAttribute('id');
        this.currentCol = document.getElementById(colId);
        this.blockWindow.open();
    }

    getColumn(containerId, columnId) {
        const container = this.content.find(cont => cont.id === containerId);
        if (!container) {
            console.error(`Container with id ${containerId} not found`);
            return;
        }

        const column = container.columns.find(col => col.id === columnId);
        if (!column) {
            console.error(`Column with id ${columnId} not found in container ${containerId}`);
            return;
        }

        return column;
    }

    registerLayout(id) {
        const layout = {};
        layout.id = id;
        layout.columns = [];

        const columns = document.getElementById(id).querySelectorAll('.pb__column');

        columns.forEach((el) => {
            const columnId = el.getAttribute('id');
            const column = {
                id: columnId,
                blocks: []
            }

            layout.columns.push(column);
        });

        this.content.push(layout);
    }

    handleFocus(event, node) {
        if (node.contains(event.target)) {
            node.classList.remove('not-focused')
            node.classList.add('focused')
            this.selectedBlock = node;
            this.showBlockToolbar();
        } else {
            node.classList.add('not-focused')
            node.classList.remove('focused')
        }
    }

    handleLayoutFocus(event, node) {
        if (node.contains(event.target)) {
            this.activeLayout = node;
            node.classList.add('active');
            this.showLayoutToolbar();
        } else {
            node.classList.remove('active');
        }
    }

    showBlockToolbar() {
        const toolbar = document.getElementById('blockTools');

        if (this.selectedBlock) {
            this.selectedBlock.prepend(toolbar);
            toolbar.style.display = 'flex';
            return;
        }

        toolbar.style.display = 'none';
    }

    showLayoutToolbar() {
        const toolbar = document.getElementById('layoutTools');

        if (this.activeLayout) {
            this.activeLayout.prepend(toolbar);
            toolbar.style.display = 'flex';
            return;
        }

        toolbar.style.display = 'none';
    }

    blockUp() {
        if (this.selectedBlock) {
            const prevBlock = this.selectedBlock.previousSibling;

            if (prevBlock) {
                this.selectedBlock.closest('.col-content').insertBefore(this.selectedBlock, prevBlock);
            }
        }
    }

    blockDown() {
        if (this.selectedBlock) {
            const nextBlock = this.selectedBlock.nextSibling;

            if (nextBlock) {
                insertAfter(this.selectedBlock, nextBlock);
            }
        }

        function insertAfter(newNode, referenceNode) {
            referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
        }

    }

    blockDelete() {
        const toolbar = document.getElementById('blockTools');
        toolbar.style.display = 'none';
        document.querySelector('body').append(toolbar);
        this.selectedBlock.remove();
    }

    layoutUp() {
        if (this.activeLayout) {
            const prevBlock = this.activeLayout.previousSibling;

            if (prevBlock) {
                document.getElementById('pb-content').insertBefore(this.activeLayout, prevBlock);
            }
        }
    }

    layoutDown() {
        if (this.selectedBlock) {
            const nextBlock = this.activeLayout.nextSibling;

            if (nextBlock) {
                insertAfter(this.activeLayout, nextBlock);
            }
        }

        function insertAfter(newNode, referenceNode) {
            referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
        }

    }

    layoutDelete() {
        const toolbar = document.getElementById('layoutTools');
        const blockTools = document.getElementById('blockTools');
        toolbar.style.display = 'none';
        blockTools.style.display = 'none';
        document.querySelector('body').append(toolbar, blockTools);
        this.activeLayout.remove();
    }

    save() {
        const data = (new DataCollector(this.blocks)).collect();
        const client = new HttpClient();

        client.post('/pagebuilder/save', data).then(() => {
            const successMessageContainer = document.createElement('div');
            successMessageContainer.classList.add('pb__success-message')
            const successMessage = document.createElement('p');
            successMessage.innerText = 'Successfully updated';
            successMessageContainer.append(successMessage);

            document.querySelector('body').append(successMessageContainer);

            setTimeout(() => {
                successMessageContainer.remove();
            }, 2000)
        });
    }

    async loadData() {
        const self = this;

        for (const layout of pageData) {
            await self.addLayout(layout.name, layout.columns)
        }
    }

    processLayoutData(layout, data) {
        const layoutColumns = layout.querySelectorAll('.pb__column');

        for (let i = 0; i < layoutColumns.length; i++) {
            this.pushColumnData(layoutColumns[i], data[i]);
        }
    }

    async pushColumnData(column, data) {
        for (const block of data.blocks) {
            await this.addBlock(block.name, column, block.data);
        }
    }
}