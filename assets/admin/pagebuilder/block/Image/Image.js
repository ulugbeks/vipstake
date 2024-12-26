import './style.scss';
import uploadFile from "../../components/UploadHandler";

export default class ImageBlock {
    constructor(data, name) {
        this.data = data;
        this.id = data.id;
        this.name = name;
        this.node = document.getElementById(this.id);
        this.uploadArea = this.node.querySelector('.pb__image-block_dropzone');
        this.fileInput = this.node.querySelector('.pb__image-block_input');
        this.preview = this.node.querySelector('.pb__image-block_preview');
        this.handleBlockEvents();
    }

    handleBlockEvents() {
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

    getData() {
        const img = this.node.querySelector('.preview img');
        const src = img.getAttribute('src');

        if (!src.length) {
            return null;
        }

        return {
            url: src
        };
    }

    setData(data) {
        this.showPreview(data.url)
    }
}