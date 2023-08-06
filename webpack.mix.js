const mix = require('laravel-mix');

mix.js('resources/js/bootstrap.js', 'public/js')
    .scripts([
        'public/js/bootstrap.js',
        'public/vendor/bootstrap/js/bootstrap.bundle.min.js',
        'public/vendor/jquery/font-awesome-icons/all.min.js',
    ], 'public/js/main.js')
    .sass('resources/scss/main.scss', 'public/css')
    .options({processCssUrls: false})
    .sourceMaps(true, 'source-map')
