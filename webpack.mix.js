
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 30 Mar 2021 17:34:41 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [require("tailwindcss"),]);

if (mix.inProduction()) {
    mix.version();
}
