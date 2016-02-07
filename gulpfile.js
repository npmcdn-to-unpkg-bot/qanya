var elixir = require('laravel-elixir');




//https://github.com/FabioAntunes/laravel-elixir-wiredep
require('laravel-elixir-wiredep');

https://www.npmjs.com/package/laravel-elixir-livereload
require('laravel-elixir-livereload');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.livereload([ 'app/**/*', 'public/**/*', 'resources/views/**/*' ]);
    mix.scriptsIn('public/assets/js');
    mix.styles([
        //'normalize.css',
        'main.css'
    ], 'public/assets/css');
    mix.wiredep({src: '/resources/views/layouts/app.blade.php'});
});