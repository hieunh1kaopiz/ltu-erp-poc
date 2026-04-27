const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Build assets cho LTU ERP. Output:
 |   public/js/app.js
 |   public/css/app.css
 |   public/mix-manifest.json   (Ansible POST_CHECK sẽ verify file này)
 |
 */

mix.js('resources/js/app.js', 'public/js')
   .vue({ version: 2 })
   .sass('resources/sass/app.scss', 'public/css')
   .version();
