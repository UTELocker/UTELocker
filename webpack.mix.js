const mix = require('laravel-mix');

mix.scripts([
        'public/vendor/moment/moment-with-locales.min.js',
        'public/vendor/moment/moment-timezone-with-data-10-year-range.js',
        'public/vendor/bootstrap/js/bootstrap.bundle.min.js',
        'public/vendor/jquery/font-awesome-icons/all.min.js',
        'public/vendor/jquery/select2/select2.min.js',
        'public/vendor/froiden-helper/helper.js',

        'node_modules/dropify/src/js/dropify.js',
        'node_modules/sweetalert2/dist/sweetalert2.all.min.js',
        'node_modules/bootstrap-select/js/bootstrap-select.js',

        'resources/js/app.js',
        'resources/js/main.js',
        'resources/js/common.js',
    ], 'public/js/main.js')
    .sass('resources/scss/main.scss', 'public/css')
    .options({processCssUrls: false})
    .sourceMaps(true, 'source-map')
