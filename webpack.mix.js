const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/setting.js', 'public/js')
    .js('resources/js/users.js', 'public/js')
    .js('resources/js/category.js', 'public/js')
    .js('resources/js/equipment.js', 'public/js')
    .js('resources/js/booking.js', 'public/js')
    .js('resources/js/tracking.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/sidebar.scss', 'public/css')
    .sass('resources/sass/setting.scss', 'public/css');
