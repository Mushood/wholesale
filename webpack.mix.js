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

mix.copy('resources/css/flaticon.css', 'public/css/flaticon.css')
   .copy('resources/css/font-awesome.min.css', 'public/css/font-awesome.min.css')
   .js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');
