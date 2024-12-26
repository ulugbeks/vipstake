import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

import 'filepond/dist/filepond.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'
import {FilePond} from "filepond";

$.fn.filepond.registerPlugin(FilePondPluginImagePreview);



$('[data-gallery]').each(function () {
    let files = [];
    let uploadedFiles = $(this).find('[type=hidden]').val();

    try {
        uploadedFiles = JSON.parse(uploadedFiles);
    } catch (e) {

    }

    if(uploadedFiles){
        uploadedFiles.forEach((file) => {
            files.push({
                source: file,
                options: {
                    type: 'local'
                }
            })
        })
    }

    $(this).find('.filepond').filepond({
        server: {
            process: '/admin/ajax/gallery-upload',
            load: '/admin/ajax/load-images?image='
        },
        allowReorder: true,
        allowMultiple: true,
        allowDrop: true,
        imagePreviewHeight: 230,
        files: files
    });
})

$('.filepond').on('FilePond:reorderfiles', function (e) {
    const files = $(e.target).filepond('getFiles');
    let urls = [];
    files.forEach((file) => {

        console.log(file.serverId);
        urls.push(file.serverId);
    })

    const gallery = $(e.target).closest('[data-gallery]').attr('data-gallery');
    syncLangGalleriesValues(gallery, urls);
})

$('.filepond').on('FilePond:removefile', function (e) {
    const files = $(e.target).filepond('getFiles');
    let urls = [];
    files.forEach((file) => {
        urls.push(file.serverId);
    })

    const gallery = $(e.target).closest('[data-gallery]').attr('data-gallery');
    syncLangGalleriesValues(gallery, urls)
})

$('.filepond').on('FilePond:processfiles', function (e) {
    const files = $(e.target).filepond('getFiles');
    let urls = [];

    files.forEach((file) => {
        urls.push(file.serverId);
    })

    const gallery = $(e.target).closest('[data-gallery]').attr('data-gallery');
    syncLangGalleriesValues(gallery, urls)
})

$('.filepond').on('FilePond:processfile', function (e) {
    const files = $(e.target).filepond('getFiles');
    let urls = [];

    files.forEach((file) => {
        urls.push(file.serverId);
    })

    const gallery = $(e.target).closest('[data-gallery]').attr('data-gallery');
    syncLangGalleriesValues(gallery, urls)
})


function syncLangGalleriesValues(id, values) {
    $('[data-gallery="' + id + '"]').each(function () {
        $(this).find('[type=hidden]').val(JSON.stringify(values));
    })
}