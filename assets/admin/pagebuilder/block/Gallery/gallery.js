import './style.scss';
import uploadFile from "../../components/UploadHandler";

export default class Gallery {
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
        for (const file of files) {
            if (file && file.type.startsWith('image/')) {
                uploadFile(file).then((result) => {
                    this.showPreview(result.url);
                });
            }
        }
    }

    showPreview(imageSrc) {
        const image = this.createPreview(imageSrc);
        this.preview.append(image);
        this.uploadArea.style.display = 'none';
        this.preview.style.display = 'flex';
    }

    getTrashIcon() {
        return "<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" fill=\"#000000\" version=\"1.1\" id=\"Capa_1\" width=\"800px\" height=\"800px\" viewBox=\"0 0 485 485\" xml:space=\"preserve\">\n" +
            "<g>\n" +
            "\t<g>\n" +
            "\t\t<rect x=\"67.224\" width=\"350.535\" height=\"71.81\"/>\n" +
            "\t\t<path d=\"M417.776,92.829H67.237V485h350.537V92.829H417.776z M165.402,431.447h-28.362V146.383h28.362V431.447z M256.689,431.447    h-28.363V146.383h28.363V431.447z M347.97,431.447h-28.361V146.383h28.361V431.447z\"/>\n" +
            "\t</g>\n" +
            "</g>\n" +
            "</svg>";
    }

    createPreview(imageSrc) {
        const imageContainer = document.createElement('div');
        imageContainer.classList.add('pb__gallery-img-wrapper');

        const deleteButton = document.createElement('span');
        deleteButton.classList.add('delete');
        deleteButton.innerHTML = this.getTrashIcon();
        deleteButton.addEventListener('click', function () {
            imageContainer.remove();
        })

        const image = document.createElement('img');
        image.src = imageSrc;

        imageContainer.append(image, deleteButton);

        return imageContainer;
    }

    getData() {
        const data = [];
        const images = this.node.querySelectorAll('.pb__gallery-img-wrapper');

        if (!images.length) {
            return null;
        }

        images.forEach((image) => {
            data.push(image.querySelector('img').getAttribute('src'));
        })

        return data;
    }

    setData(data) {
        for (const image of data) {
            this.showPreview(image);
        }
    }
}