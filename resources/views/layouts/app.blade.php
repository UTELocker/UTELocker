<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset(globalSettings()->favicon) }}">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('vendor/css/font-awesome-icons/all.min.css') }}">

    <!-- Simple Line Icons -->
    <link rel="stylesheet" href="{{ asset('vendor/css/simple-line-icons/simple-line-icons.css') }}">

    <!-- Datepicker -->
    <link rel="stylesheet" href="{{ asset('vendor/css/datepicker/datepicker.min.css') }}">

    <!-- TimePicker -->
    <link rel="stylesheet" href="{{ asset('vendor/css/bootstrap-timepicker/bootstrap-timepicker.min.css') }}">

    <!-- Select Plugin -->
    <link rel="stylesheet" href="{{ asset('vendor/css/select2/select2.min.css') }}">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('vendor/css/bootstrap-icons/bootstrap-icons.css') }}">

    @stack('datatable-styles')

    <!-- Template CSS  -->
    <link type="text/css" rel="stylesheet" media="all" href="{{ asset('css/main.css') }}">

    <title>{{ $pageTitle }}</title>
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset(globalSettings()->favicon) }}">
    <meta name="theme-color" content="#ffffff">
    @stack('styles')

    <style>
        :root {
            --fc-border-color: #E8EEF3;
            --fc-button-text-color: #99A5B5;
            --fc-button-border-color: #99A5B5;
            --fc-button-bg-color: #ffffff;
            --fc-button-active-bg-color: #171f29;
            --fc-today-bg-color: #f2f4f7;
        }

        .fc a[data-navlink] {
            color: #99a5b5;
        }
        .ql-editor p{
            line-height: 1.42;
        }
        .ql-container .ql-tooltip {
            left: 8.5px !important;
            top: -17px !important;
        }
        .table [contenteditable="true"]{
            height: 55px;
        }
        .table [contenteditable="true"]:hover::after {
            content: "{{ __('app.clickEdit') }}" !important;
        }
        .table [contenteditable="true"]:focus::after {
            content: "{{ __('app.anywhereSave') }}" !important;
        }
        .table-bordered .displayName {
            padding: 17px;
        }
    </style>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
</head>
<body id="body">
    @includeIf('super-admin.sections.topbar')
    @include('sections.sidebar')

    <div class="body-wrapper clearfix">
        <section class="main-container bg-additional-grey mb-5 mb-sm-0" id="fullscreen">
            <div class="preloader-container d-flex justify-content-center align-items-center">
                <div class="spinner-border" role="status" aria-hidden="true"></div>
            </div>

            @yield('filter-section')

            <x-app-title class="d-block d-lg-none" :pageTitle="$pageTitle"></x-app-title>

            @yield('content')

        </section>
    </div>

    @include('sections.modals')

    <!-- Global Required Javascript -->
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        const MODAL_DEFAULT = '#myModalDefault';
        const MODAL_LG = '#myModal';
        const MODAL_XL = '#myModalXl';
        const MODAL_HEADING = '#modelHeading';
        const RIGHT_MODAL = '#task-detail-1';
        const RIGHT_MODAL_CONTENT = '#right-modal-content';
        const RIGHT_MODAL_TITLE = '#right-modal-title';

        const dropifyMessages = {
            default: "@lang('app.dragDrop')",
            replace: "@lang('app.dragDropReplace')",
            remove: "@lang('app.remove')",
            error: "@lang('messages.errorOccured')",
        };

        const datepickerConfig = {
            formatter: (input, date, instance) => {
                input.value = moment(date).format('{{ siteGroupOrGlobalSetting()->moment_format }}')
            },
            showAllDates: true,
            customDays: {!!  json_encode(\App\Models\GlobalSetting::getDaysOfWeek())!!},
            customMonths: {!!  json_encode(\App\Models\GlobalSetting::getMonthsOfYear())!!},
            customOverlayMonths: {!!  json_encode(\App\Models\GlobalSetting::getMonthsOfYear())!!},
            overlayButton: "@lang('app.submit')",
            overlayPlaceholder: "@lang('app.enterYear')",
        };
    </script>
    <script>
        let quillArray = {};

        function quillImageLoad(ID) {
            const quillContainer = document.querySelector(ID);
            quillArray[ID] = new Quill(ID, {
                modules: {
                    toolbar: [
                        [{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }],
                        [{
                            header: [1, 2, 3, 4, 5, false]
                        }],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        // ['image', 'code-block', 'link','video'],
                        // [{
                        //     'direction': 'rtl'
                        // }],
                        // ['clean']
                    ],
                    clipboard: {
                        matchVisual: false
                    },
                    "emoji-toolbar": true,
                    "emoji-textarea": true,
                    "emoji-shortname": true,
                },
                theme: 'snow',
                bounds: quillContainer
            });
            $.each(quillArray, function (key, quill) {
                quill.getModule('toolbar').addHandler('image', selectLocalImage);
            });


        }
            function destory_editor(selector){
                if($(selector)[0])
                {
                    var content = $(selector).find('.ql-editor').html();
                    $(selector).html(content);

                    $(selector).siblings('.ql-toolbar').remove();
                    $(selector + " *[class*='ql-']").removeClass (function (index, class_name) {
                    return (class_name.match (/(^|\s)ql-\S+/g) || []).join(' ');
                    });

                    $(selector + "[class*='ql-']").removeClass (function (index, class_name) {
                    return (class_name.match (/(^|\s)ql-\S+/g) || []).join(' ');
                    });
                }
                else
                {
                    console.error('editor not exists');
                }
            }
        function quillMention(atValues,ID) {
            const mentionItemTemplate = '<div class="mention-item"> <img src="{image}" class="align-self-start mr-3 taskEmployeeImg rounded">{name}</div>';

            const customRenderItem = function(item, searchTerm) {
                const html = mentionItemTemplate.replace('{image}', item.image).replace('{name}', item.value);
                return html;
            }
            let placeholder;
            if (ID === '#submitTexts') {
                placeholder = "@lang('placeholders.message')";
            } else {
                placeholder = '';
            }

            var quillEditor = new Quill(ID, {
                placeholder: placeholder,
                modules: {
                    magicUrl: {
                        urlRegularExpression: /(https?:\/\/[\S]+)|(www.[\S]+)|(tel:[\S]+)/g,
                        globalRegularExpression: /(https?:\/\/|www\.|tel:)[\S]+/g,
                    },
                    toolbar: [
                        [{
                            header: [1, 2, 3, 4, 5, false]
                        }],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        ['image', 'code-block', 'link','video'],
                        [{
                            'direction': 'rtl'
                        }],
                        ['clean']
                    ],
                    mention: {
                        allowedChars: /^[A-Za-z\sÅÄÖåäö]*$/,
                        mentionDenotationChars: ["@", "#"],
                        source: function(searchTerm, renderList, mentionChar) {
                        let values;
                        if (mentionChar === "@") {
                            values = atValues;
                        } else {
                            values = hashValues;
                        }

                        if (searchTerm.length === 0) {
                            renderList(values, searchTerm);

                        } else {
                            const matches = [];
                            for (i = 0; i < values.length; i++)
                            if (
                                ~values[i].value
                                .toLowerCase()
                                .indexOf(searchTerm.toLowerCase())
                            )
                                matches.push(values[i]);
                            renderList(matches, searchTerm);
                        }
                        },
                        renderItem: customRenderItem,

                    },

                },
                theme: 'snow'
            });
        }
         /**
         * click to open user profile
         *
         */
        window.addEventListener('mention-clicked', function ({ value }) {
        if (value?.link) {
            window.open(value.link, value?.target ?? '_blank');
        }
        });
        /**
         * Step1. select local image
         *
         */
        function selectLocalImage() {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.click();

            // Listen upload local image and save to server
            input.onchange = () => {
                const file = input.files[0];

                // file type is only image.
                if (/^image\//.test(file.type)) {
                    saveToServer(file);
                } else {
                    console.warn('You could only upload images.');
                }
            };
        }

        /**
         * Step2. save to server
         *
         * @param {File} file
         */
        function saveToServer(file) {

        }

        function insertToEditor(url) {
            // push image url to rich editor.
            $.each(quillArray, function (key, quill) {
                try {
                    let range = quill.getSelection();
                    quill.insertEmbed(range.index, 'image', url);
                } catch (err) {
                }
            });
        }
    </script>
    @stack('scripts')
    <script>
        $(window).on('load', function () {
            UTELocker.common.init();
            $(".preloader-container").fadeOut("slow", function () {
                $(this).removeClass("d-flex");
            });
        });
    </script>
</body>
</html>
