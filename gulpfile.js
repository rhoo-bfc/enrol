var elixir = require('laravel-elixir');

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
    
    mix.styles([
        'foundation.css',
        'foundation-icons/foundation-icons.css',
        'app.css'
    ], 'public/build/css/app.css','public/css');
    
    mix.version('public/build/css/app.css');
    
    elixir(function(mix) {
        mix.browserify('stopwatch.js', 'public/build/js/stopwatch.js','public/js');
    });
    
    mix.scripts([
        'vendor/jquery.js',
        'vendor/jquery-ui-1.12.0.custom/jquery-ui.js',
        'vendor/jquery.clock.js',
        'vendor/jquery.marquee.js',
        'vendor/what-input.js',
        'vendor/foundation.js',
        'app.js'
    ], 'public/build/js/app.js','public/js');
    
    mix.version(['public/build/css/app.css' , 'public/build/js/app.js' ]);
    
});
