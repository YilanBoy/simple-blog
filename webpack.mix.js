const mix = require('laravel-mix');
require('laravel-mix-purgecss');

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
    .js('resources/js/sweet-alert.js', 'public/js')
    .js('resources/js/tagify.js', 'public/js')
    .js('resources/js/editor.js', 'public/js')
    .js('resources/js/algolia.js', 'public/js')
    .js('resources/js/scroll-to-top-btn.js', 'public/js')
    .copy('node_modules/sharer.js/sharer.min.js', 'public/js/sharer.min.js')
    .sass('resources/sass/app.scss', 'public/css')
    .purgeCss({
        extend: {
            content: [
                'vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
                'storage/framework/views/*.php',
                'resources/js/*.js',
            ],
        },
    })
    .copy(
        'node_modules/@yaireo/tagify/dist/tagify.css',
        'public/css/tagify.css'
    )
    .options({
        terser: {
            extractComments: false
        }
    });
