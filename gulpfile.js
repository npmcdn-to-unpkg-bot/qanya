var gulp  = require('gulp');
var elixir = require('laravel-elixir');

//https://github.com/FabioAntunes/laravel-elixir-wiredep
//require('laravel-elixir-wiredep');

//https://www.npmjs.com/package/laravel-elixir-livereload
require('laravel-elixir-livereload');

//https://scotch.io/tutorials/use-gulp-to-start-a-laravel-php-server
var php = require('gulp-connect-php');

//https://www.npmjs.com/package/gulp-exec
var exec = require('gulp-exec');

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

   //for running laravel
    php.server({
        base: './public',
        port: 8888,
        hostname: '0.0.0.0' //use this for outside connection
    });

    mix.livereload([ 'app/**/*', 'public/**/*',
                     'resources/views/**/*',
                     'resources/assets/**/*']);
    //mix.scripts(['node_modules/socket.io/lib/socket.js']);
    mix.scriptsIn('public/assets/js');
    mix.styles([
        //'normalize.css',
        'main.css'
    ], 'public/assets/css');
    //mix.wiredep({src: '/resources/views/layouts/app.blade.php'});

});