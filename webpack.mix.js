let mix = require('laravel-mix');

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

mix.js('resources/assets/front/js/app.js', 'public/front/js')
   .sass('resources/assets/front/sass/app.scss', 'public/front/css')
   .options({
	    extractVueStyles: true
    })
   .browserSync({
       proxy: 'localhost:8000'
   });
