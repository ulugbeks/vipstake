export default class LabelsField {
    constructor(id) {
        this.node = document.getElementById(id);
        this.addButton = this.node.querySelector('.labels__add');
        this.labelAddWindow = this.node.querySelector('.label__add-window');
        this.addLabelButton = this.node.querySelector('.insert-label');
        this.labelsContainer = this.node.querySelector('.labels-container');
        this.data = this.node.querySelector('[type=hidden]').value;
        this.labels = this.data.length ? JSON.parse(this.data) : {};

        this.handleEvents();
    }

    handleEvents() {
        if(Object.keys(this.labels).length){
            for(const labelKey in this.labels){
                const labelData = this.labels[labelKey];
                const label = this.createLabel(labelData.text, labelData.color, labelKey);
                this.labelsContainer.append(label);
            }
        }

        this.addButton.addEventListener('click', (event) => this.addLabel());
        this.addLabelButton.addEventListener('click', () => this.insertLabel());
    }

    addLabel() {
        this.openLabelWindow();
    }

    openLabelWindow() {
        this.labelAddWindow.style.display = 'flex';
    }

    closeLabelWindow() {
        this.labelAddWindow.style.display = 'none';
    }

    insertLabel() {
        const textInput = this.node.querySelector('.label-name');
        const text = textInput.value;
        const color = this.node.querySelector('.label-color').value;
        const id = 'label_' + Date.now();
        const label = this.createLabel(text, color, id);
        this.labelsContainer.append(label);
        textInput.value = '';
        this.closeLabelWindow();
    }

    createLabel(text, color, id) {
        const close = document.createElement('span');
        close.innerText = '✕';
        close.classList.add('delete')

        const label = document.createElement('div');
        label.classList.add('labels__item');
        label.innerText = text;
        label.style.backgroundColor = color;
        label.setAttribute('data-color', color);
        label.id = id;

        close.addEventListener('click', () => this.deleteLabel(label));
        label.append(close)

        this.labels[id] = {
            text: text,
            color: color
        }

        this.node.querySelector('[type=hidden]').value = JSON.stringify(this.labels)

        return label;
    }

    deleteLabel(label) {
        const id = label.id;
        delete this.labels[id];
        this.node.querySelector('[type=hidden]').value = JSON.stringify(this.labels)
        label.remove();
    }
}