const mix = require('laravel-mix');
const vueVersion = 3;
const webpack = require('webpack');

mix.scripts([
        'public/vendor/moment/moment-with-locales.min.js',
        'public/vendor/moment/moment-timezone-with-data-10-year-range.js',
        'public/vendor/bootstrap/js/bootstrap.bundle.min.js',
        'public/vendor/jquery/font-awesome-icons/all.min.js',
        'public/vendor/jquery/select2/select2.min.js',
        'public/vendor/froiden-helper/helper.js',
        'public/vendor/jquery/date-range-picker/datepicker.min.js',

        'node_modules/dropify/src/js/dropify.js',
        'node_modules/bootstrap-select/js/bootstrap-select.js',
        'node_modules/quill/dist/quill.min.js',
        'node_modules/quill-emoji/dist/quill-emoji.js',
        'node_modules/quill-mention/dist/quill.mention.min.js',
        'node_modules/quill-magic-url/dist/index.js',

        'resources/js/app.js',
        'resources/js/main.js',
        'resources/js/common.js',
    ], 'public/js/main.js')
    .sass('resources/scss/main.scss', 'public/css')
    .options({processCssUrls: false})
    .sourceMaps(true, 'source-map')

mix.scripts([
    'resources/js/bulkCreate.js',
], 'public/js/bulkCreate.js')

// Portal app
mix.js('resources/js/portalApp/portalMain.js', 'public/js/portalMain.js')
    .vue({version: vueVersion})
    .sass('resources/scss/portalApp.scss', 'public/css/portalApp.css')
