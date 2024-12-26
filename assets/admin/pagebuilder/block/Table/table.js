import './style.scss';
import ContextMenu from "../../window/ContextMenu";

export default class Table {
    constructor(data, name) {
        this.placeholder = 'Enter text';
        this.data = data;
        this.name = name;
        this.id = data.id;
        this.node = document.getElementById(this.id);
        this.tableContainer = this.node.querySelector('.table-content')
        this.table = null;
        this.columnsCount = 2;
        this.settingsWindow = this.node.querySelector('.pb__table-settings')
        this.contextMenu = new ContextMenu(this.node.querySelector('.table__context-menu'));

        this.layouts = {
            top: 'layout-top',
            left: 'layout-left'
        }
        this.alignment = {
            left: 'align-left',
            center: 'align-center'
        }
        this.style = {
            default: 'default',
            color: 'colors-table',
            bold: 'bold'
        }

        this.options = {
            layout: this.layouts.top,
            alignment: this.alignment.left,
            style: this.style.default
        }

        this.selectedCell = null;

        this.init();
        this.handleBlockEvents();
    }

    handleBlockEvents() {
        this.node.querySelector('.table__add-col').addEventListener('click', () => this.insertColumn());
        this.node.querySelector('.table__add-row').addEventListener('click', () => this.insertRow(this.getColumnsCount()));
        document.addEventListener('click', (event) => this.handleFocus(event));
        this.node.querySelector('.pb__table-settings-button').addEventListener('click', () => this.toggleSettingsWindow())
        this.node.querySelector('.table__layout-select').addEventListener('change', (event) => this.handleLayoutSelect(event.target.value))
        this.node.querySelector('.table__align-select').addEventListener('change', (event) => this.handleAlignSelect(event.target.value))
        this.node.querySelector('.table__style-select').addEventListener('change', (event) => this.handleStyleSelect(event.target.value))
        this.node.querySelector('.table__delete-col').addEventListener('click', () => this.handleColumnDelete())
        this.node.querySelector('.table__delete-row').addEventListener('click', () => this.handleRowDelete())
        document.addEventListener('click', () => this.contextMenu.close());
    }

    init() {
        const table = document.createElement('table');
        this.node.classList.add('focused');
        this.table = table;
        table.setAttribute('contenteditable', true);
        table.classList.add(this.layouts.top);
        this.tableContainer.append(table);
        this.insertRow(this.columnsCount);
    }

    insertRow(cols, cellsData = null) {
        const row = document.createElement('tr');

        for (let i = 0; i < cols; i++) {
            const td = document.createElement('td');
            td.setAttribute('data-col-index', i + 1);
            td.innerText = this.placeholder;
            td.addEventListener('contextmenu', (event) => this.handleCellClick(event));
            if (cellsData) {
                td.innerText = cellsData[i];
            }
            row.append(td);
        }

        this.table.append(row);
    }

    getColumnsCount() {
        if (this.table.firstChild) {
            this.columnsCount = this.table.firstChild.querySelectorAll('td').length;
            return this.columnsCount;
        }

        return 0;
    }

    insertColumn() {
        this.columnsCount += this.getColumnsCount() + 1;
        const columnIndex = this.table.firstChild ? parseInt(this.table.firstChild.children.length) + 1 : 1;

        this.table.querySelectorAll('tr').forEach((row) => {
            const td = document.createElement('td');
            td.setAttribute('data-col-index', columnIndex)
            td.innerText = this.placeholder;
            td.addEventListener('contextmenu', (event) => this.handleCellClick(event));
            row.append(td);
        })
    }

    handleFocus(event) {
        if (this.node.contains(event.target)) {
            this.node.classList.add('focused')
        } else {
            this.node.classList.remove('focused')
        }
    }

    toggleSettingsWindow() {
        if (this.settingsWindow.classList.contains('opened')) {
            this.settingsWindow.classList.remove('opened');
            return;
        }

        this.settingsWindow.classList.add('opened');
    }

    handleLayoutSelect(layout) {
        Object.entries(this.layouts).forEach(([key, value]) => {
            if (key !== layout) {
                this.table.classList.remove(value);
                return;
            }

            this.options.layout = key;
            this.table.classList.add(value);
        });
    }


    handleAlignSelect(layout) {
        Object.entries(this.alignment).forEach(([key, value]) => {
            if (key !== layout) {
                this.table.classList.remove(value);
                return;
            }

            this.options.alignment = key;
            this.table.classList.add(value);
        });
    }

    handleStyleSelect(layout) {
        Object.entries(this.style).forEach(([key, value]) => {
            if (key !== layout) {
                this.table.classList.remove(value);
                return;
            }

            this.options.style = key;
            this.table.classList.add(value);
        });
    }

    handleCellClick(event) {
        if (event.target.tagName.toLowerCase() === 'td') {
            this.contextMenu.open(event);
            this.selectedCell = event.target;
        } else {
            this.contextMenu.close();
        }
    }

    handleColumnDelete() {
        const columnIndex = parseInt(this.selectedCell.getAttribute('data-col-index'));

        this.table.querySelectorAll('td').forEach((cell) => {
            if (columnIndex === parseInt(cell.getAttribute('data-col-index'))) {
                cell.remove();
            }
        })

        this.updateColumnIndexes();
        this.contextMenu.close();
    }

    handleRowDelete() {
        this.selectedCell.closest('tr').remove();
        this.contextMenu.close();
    }

    updateColumnIndexes() {
        this.table.querySelectorAll('tr').forEach((column) => {
            let index = 0;

            column.querySelectorAll('td').forEach((cell) => {
                cell.setAttribute('data-col-index', ++index)
            })
        })
    }

    getData() {
        const data = {};
        data.options = this.options;
        data.table = [];

        const rows = this.table.querySelectorAll('tr');

        if (!rows.length) {
            return null;
        }

        rows.forEach((row) => {
            const cells = row.querySelectorAll('td');

            if (cells.length) {
                const rowData = [];
                cells.forEach((cell) => {
                    rowData.push(cell.innerText);
                })

                data.table.push(rowData);
            }
        })

        if (!data.table.length) {
            return null;
        }

        return data;
    }

    setData(data) {
        console.log(data);
        this.table.innerHTML = '';
        data.table.forEach((row) => {
            this.insertRow(row.length, row);
        });
        this.setOptions(data.options);
    }

    setOptions(options){
        this.node.querySelectorAll('.table__layout-select option').forEach((option) => {
            if(option.value === options.layout){
                option.selected = true;
                this.handleLayoutSelect(options.layout);
            }
        });

        this.node.querySelectorAll('.table__align-select option').forEach((option) => {
            if(option.value === options.alignment){
                option.selected = true;
                this.handleAlignSelect(options.alignment);
            }
        });

        this.node.querySelectorAll('.table__style-select option').forEach((option) => {
            if(option.value === options.style){
                option.selected = true;
                this.handleStyleSelect(options.style);
            }
        });
    }
}