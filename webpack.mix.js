const mix = require('laravel-mix');

const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin');

var webpackConfig = {
    plugins: [
        new VuetifyLoaderPlugin()
    ],
    resolve: {
        alias: {
            '@': __dirname + '/resources/js',
        },
    },
};

mix.webpackConfig(webpackConfig);

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

mix.js('resources/welcome/js/welcome.js', 'public/js',)
    .sass('resources/welcome/style/welcome.scss', 'public/css');

mix.js('resources/js/app.js', 'public/js',)
    .sass('resources/sass/app.scss', 'public/css');