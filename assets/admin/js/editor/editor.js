import EditorJS from '@editorjs/editorjs';
import {LayoutBlockTool} from 'editorjs-layout';
import Header from '@editorjs/header'
import List from './list'
import Table from './table'
import Paragraph from '@editorjs/paragraph'
import ImageTool from '@editorjs/image'
import Embed from '@editorjs/embed'
import RawTool from '@editorjs/raw';
// import LinkAutocomplete from './link-autocomplete'
import Hyperlink from 'editorjs-hyperlink'
import ColorPlugin from 'editorjs-text-color-plugin'


const editors = {};

function initEditor(holder, lang) {
    const editorJSConfig = {
        tools: {
            header: Header,
            list: {
                class: List,
                inlineToolbar: true
            },
            table: Table,
            image: {
                class: ImageTool,
                config: {
                    endpoints: {
                        byFile: '/admin/ajax/image-upload', // Your backend file uploader endpoint
                        // byUrl: admin_editor_image_ajax_url, // Your endpoint that provides uploading by Url
                    }
                }
            },
            // link: {
            //     class: LinkAutocomplete,
            //     config: {
            //         endpoint: '/admin/ajax/link-list',
            //         queryParam: 'search'
            //     }
            // },
            embed: {
                class: Embed,
                inlineToolbar: true,
                config: {
                    services: {
                        youtube: true,
                        coub: true
                    }
                }
            },
            // hyperlink: {
            //     class: Hyperlink,
            //     config: {
            //         shortcut: 'CMD+L',
            //         target: '_blank',
            //         rel: 'nofollow',
            //         availableTargets: ['_blank', '_self'],
            //         availableRels: ['author', 'noreferrer'],
            //         validate: false,
            //     }
            // },
            Color: {
                class: ColorPlugin, // if load from CDN, please try: window.ColorPlugin
                config: {
                    colorCollections: ['#E74C26', '#9C27B0', '#673AB7', '#3F51B5', '#0070FF', '#03A9F4', '#00BCD4', '#4CAF50', '#8BC34A', '#CDDC39', '#FFF'],
                    defaultColor: '#E74C26',
                    type: 'text',
                    customPicker: true // add a button to allow selecting any colour
                }
            },
        }
    };

    const data = JSON.parse(document.querySelector('[data-content-storage="' + lang + '"]').getAttribute('value'));

    editors[lang] = new EditorJS({
        data: data,
        holder: holder,
        tools: {
            twoColumns: {
                class: LayoutBlockTool,
                config: {
                    EditorJS,
                    editorJSConfig,
                    enableLayoutEditing: false,
                    enableLayoutSaving: false,
                    initialData: {
                        itemContent: {
                            1: {
                                blocks: [],
                            },
                            2: {
                                blocks: [],
                            },
                        },
                        layout: {
                            type: "container",
                            id: "",
                            className: "blocks-container",
                            style:
                                "",
                            children: [
                                {
                                    type: "item",
                                    id: "",
                                    className: "child-block",
                                    style: "box-shadow: 1px 1px 7px rgba(0,0,0, 0.1); ",
                                    itemContentId: "1",
                                },
                                {
                                    type: "item",
                                    id: "",
                                    className: "child-block",
                                    style: "box-shadow: 1px 1px 7px rgba(0,0,0, 0.1);",
                                    itemContentId: "2",
                                },
                            ],
                        },
                    },
                },
                shortcut: "CMD+2",
                toolbox: {
                    icon: `
              <svg xmlns='http://www.w3.org/2000/svg' width="16" height="16" viewBox='0 0 512 512'>
                <rect x='128' y='128' width='336' height='336' rx='57' ry='57' fill='none' stroke='currentColor' stroke-linejoin='round' stroke-width='32'/>
                <path d='M383.5 128l.5-24a56.16 56.16 0 00-56-56H112a64.19 64.19 0 00-64 64v216a56.16 56.16 0 0056 56h24' fill='none' stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='32'/>
              </svg>
            `,
                    title: "2 columns",
                },
            },
            header: {
                class: Header,
                inlineToolbar: ['link']
            },
            list: {
                class: List,
                inlineToolbar: true
            },
            table: {
                class: Table,
                inlineToolbar: true
            },
            paragraph: {
                class: Paragraph,
                inlineToolbar: true,
                config: {
                    placeholder: 'Почніть писати'
                }
            },
            image: {
                class: ImageTool,
                config: {
                    endpoints: {
                        byFile: '/admin/ajax/image-upload', // Your backend file uploader endpoint
                        // byUrl: admin_editor_image_ajax_url, // Your endpoint that provides uploading by Url
                    }
                }
            },
            embed: {
                class: Embed,
                inlineToolbar: true,
                config: {
                    services: {
                        youtube: true,
                        coub: true
                    }
                }
            },
            raw: {
                class: RawTool
            },
            // link: {
            //     class: LinkAutocomplete,
            //     config: {
            //         endpoint: '/admin/ajax/link-list',
            //         queryParam: 'search',
            //         locale: 'en'
            //     }
            // },
            // hyperlink: {
            //     class: Hyperlink,
            //     config: {
            //         shortcut: 'CMD+L',
            //         target: '_blank',
            //         rel: 'nofollow',
            //         availableTargets: ['_blank', '_self'],
            //         availableRels: ['author', 'noreferrer'],
            //         validate: false,
            //     }
            // },
            Color: {
                class: ColorPlugin, // if load from CDN, please try: window.ColorPlugin
                config: {
                    colorCollections: ['#E74C26', '#9C27B0', '#673AB7', '#3F51B5', '#0070FF', '#03A9F4', '#00BCD4', '#4CAF50', '#8BC34A', '#CDDC39', '#FFF'],
                    defaultColor: '#E74C26',
                    type: 'text',
                    customPicker: true // add a button to allow selecting any colour
                }
            },
        },

        onChange: () => {
            editors[lang].save().then((outputData) => {
                document.querySelector('[data-content-storage="' + lang + '"]').setAttribute('value', JSON.stringify(outputData));
                $('#product_footer_save').prop('disabled', false);
            }).catch((error) => {
                console.log('Saving failed: ', error)
            });
        }
    });
}

function initEditors() {
    document.querySelectorAll('[data-content-editor]').forEach((item) => {
        initEditor(item.getAttribute('id'), item.getAttribute('data-content-editor'));
    })
}

initEditors();
