import uploadFile from "../../components/UploadHandler";

export default function createSlide(data = null) {
    const slide = document.createElement('div');
    slide.classList.add('slide-item');

    const trash = document.createElement('div');

    trash.classList.add('delete');
    trash.innerText = '×';
    trash.addEventListener('click', () => deleteSlide(slide));

    const imageUploadZone = createImageUploadZone();
    const imagePreview = createImagePreviewZone();
    const input = createFileInput();
    const textBox = createTextBox();
    slide.append(trash, imageUploadZone, imagePreview, input, textBox);
    handleImageUpload(slide);

    if (data) {
        setData(data, slide);
    }

    return slide;
}

function setData(data, block){
    const fileInput = block.querySelector('.pb__image-block_input');
    const preview = block.querySelector('.pb__image-block_preview');
    const uploadArea = block.querySelector('.pb__image-block_dropzone');

    block.querySelector('.text-box__label').innerHtml = data.caption;
    block.querySelector('.text-box__desc').innerHtml = data.text;

    if (data.img) {
        showPreview(data.img);
    }

    function showPreview(imageSrc) {
        preview.innerHTML = `<img src="${imageSrc}" alt="Image preview">`;
        uploadArea.style.display = 'none';
        preview.style.display = 'block';

        // Добавить обработчик клика на превью
        preview.querySelector('img').addEventListener('click', () => {
            fileInput.click();
        });
    }
}

function createImageUploadZone() {
    const imagePlaceHolder = document.createElement('div');
    imagePlaceHolder.classList.add('slide-item__image-upload');

    const imageDropZone = document.createElement('div');
    imageDropZone.classList.add('dropzone', 'pb__image-block_dropzone');

    const dropZonePlaceholder = document.createElement('div');
    dropZonePlaceholder.classList.add('dz-message');
    dropZonePlaceholder.innerText = 'Drop image here or click to upload';

    imageDropZone.append(dropZonePlaceholder);
    imagePlaceHolder.append(imageDropZone);

    return imagePlaceHolder;
}

function createImagePreviewZone() {
    const preview = document.createElement('div');
    preview.classList.add('preview', 'pb__image-block_preview');

    return preview;
}

function createFileInput() {
    const input = document.createElement('input');
    input.type = 'file';
    input.classList.add('pb__image-block_input');
    input.style.display = 'none';

    return input;
}

function createTextBox() {
    const box = document.createElement('div');
    box.classList.add('text-box');

    const label = document.createElement('p');
    label.classList.add('text-box__label');
    label.setAttribute('contenteditable', "true");
    label.innerText = 'Enter text';

    const desc = document.createElement('p');
    desc.classList.add('text-box__desc');
    desc.setAttribute('contenteditable', "true")
    desc.innerText = 'Enter text';

    box.append(label, desc);

    return box;
}

function handleImageUpload(imageContainer) {
    const block = imageContainer;

    const uploadArea = block.querySelector('.pb__image-block_dropzone');
    const fileInput = block.querySelector('.pb__image-block_input');
    const preview = block.querySelector('.pb__image-block_preview');
    const existingImageUrl = ""; //

    if (existingImageUrl) {
        showPreview(existingImageUrl);
    }

    uploadArea.addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', (event) => {
        handleFiles(event.target.files);
    });

    uploadArea.addEventListener('dragover', (event) => {
        event.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (event) => {
        event.preventDefault();
        uploadArea.classList.remove('dragover');
        handleFiles(event.dataTransfer.files);
    });

    function handleFiles(files) {
        const file = files[0];
        if (file && file.type.startsWith('image/')) {
            uploadFile(file).then((result) => {
                showPreview(result.url);
            });
        }
    }

    function showPreview(imageSrc) {
        preview.innerHTML = `<img src="${imageSrc}" alt="Image preview">`;
        uploadArea.style.display = 'none';
        preview.style.display = 'block';

        // Добавить обработчик клика на превью
        preview.querySelector('img').addEventListener('click', () => {
            fileInput.click();
        });
    }
}

function deleteSlide(slide) {
    const parent = slide.parentElement;
    const sliderWrapper = slide.closest('.slider-wrapper');
    slide.remove();

    const children = parent.querySelectorAll('.slide-item');
    console.log(children.length, sliderWrapper);

    if (!children.length && sliderWrapper) {
        console.log('kekekekek')
        sliderWrapper.remove();
    }
}