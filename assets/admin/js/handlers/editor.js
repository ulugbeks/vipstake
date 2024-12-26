// const editor = document.querySelectorAll('.gg_editor');

tinymce.init({
    selector: '.gg_editor',
    plugins: [
        'advlist','autolink','code',
        'lists','link','image','charmap','preview','anchor','searchreplace','visualblocks',
        'fullscreen','insertdatetime','media','table','help','wordcount'
    ],
    toolbar: 'undo redo | formatpainter casechange blocks | bold italic backcolor | ' +
        'alignleft aligncenter alignright alignjustify | ' +
        'bullist numlist checklist outdent indent | removeformat | a11ycheck code table help',
    file_picker_types: 'file image media',
    images_upload_url: '/admin/ajax/image-upload',
    table_appearance_options: false,
    table_use_colgroups: false,
    table_default_attributes: {},
    table_default_styles: {
    },
    table_class_list: [
        {title: 'None', value: ''},
        {title: 'Table Simple', value: 'table'},
        {title: 'Table With Captions', value: 'table table--top'},
        {title: 'Table With Captions At Left', value: 'table table--left'},
    ],
    table_style_by_css: true,
    relative_urls: false,
    remove_script_host: false
});