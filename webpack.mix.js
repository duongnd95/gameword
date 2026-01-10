const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js/backend.js')
    .combine([
        'public/js/backend.js',
        'public/plugins/select2.min.js',
        'public/plugins/pace/pace.min.js',
    ], 'public/js/app.js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .sass('resources/assets/sass/mystyle.scss', 'public/css/mystyle.css')
    .combine([
        'public/css/app.css',
        'node_modules/icheck/skins/square/blue.css',
        'public/plugins/select2.min.css',
        'public/plugins/pace/pace-theme-flash.min.css',
        'public/plugins/toastr/toastr.min.css',
        'public/plugins/_all-skins.min.css',
        'public/css/adminlte-app.css',
        'public/css/mystyle.css',
    ], 'public/css/all.css')

    //APP RESOURCES
    .options({ processCssUrls: false });

if (mix.config.inProduction) {
    mix.version();
}