import './style.scss';
import ContextMenu from "../../window/ContextMenu";
import uploadFile from "../../components/UploadHandler";

export default class IconLink {
    constructor(data, name) {
        this.data = data;
        this.name = name;
        this.id = data.id;
        this.node = document.getElementById(this.id);
        this.linkModal = new ContextMenu(this.node.querySelector('.link-modal'));
        this.link = this.node.querySelector('.lc__link');
        this.uploadArea = this.node.querySelector('.pb__image-block_dropzone');
        this.fileInput = this.node.querySelector('.pb__image-block_input');
        this.preview = this.node.querySelector('.pb__image-block_preview');
        this.handleBlockEvents();
    }

    handleBlockEvents() {
        this.node.querySelector('.lc__link').addEventListener('contextmenu', (event) => this.handleLinkRightClick(event));
        this.node.querySelector('.link-modal input').addEventListener('keydown', (event) => this.handleUrlInput(event));

        this.uploadArea.addEventListener('click', () => {
            this.fileInput.click();
        });

        this.fileInput.addEventListener('change', (event) => {
            this.handleFiles(event.target.files);
        });

        this.uploadArea.addEventListener('dragover', (event) => {
            event.preventDefault();
            this.uploadArea.classList.add('dragover');
        });

        this.uploadArea.addEventListener('dragleave', () => {
            this.uploadArea.classList.remove('dragover');
        });

        this.uploadArea.addEventListener('drop', (event) => {
            event.preventDefault();
            this.uploadArea.classList.remove('dragover');
            this.handleFiles(event.dataTransfer.files);
        });
    }

    handleFiles(files) {
        const file = files[0];
        if (file && file.type.startsWith('image/')) {
            uploadFile(file).then((result) => {
                this.showPreview(result.url);
            });
        }
    }

    showPreview(imageSrc) {
        this.preview.innerHTML = `<img src="${imageSrc}" alt="Image preview">`;
        this.uploadArea.style.display = 'none';
        this.preview.style.display = 'block';

        // Добавить обработчик клика на превью
        this.preview.querySelector('img').addEventListener('click', () => {
            this.fileInput.click();
        });
    }

    handleLinkRightClick(e) {
        this.linkModal.open(e);
    }

    handleUrlInput(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            const value = event.target.value;
            this.link.setAttribute('href', value);
            this.linkModal.close();
        }
    }

    getData() {
        const image = this.node.querySelector('.preview img').getAttribute('src');
        const text = this.node.querySelector('.lc__link').innerText;
        const url = this.node.querySelector('.lc__link').getAttribute('href');

        return {
            image: image,
            text: text,
            url: url
        }
    }

    setData(data) {
        this.showPreview(data.image)
        this.node.querySelector('.lc__link').innerText = data.text;
        this.node.querySelector('.link-modal input').value = data.url;
    }
}