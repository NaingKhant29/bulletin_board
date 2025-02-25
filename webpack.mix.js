const mix = require('laravel-mix');

// Copy the Bootstrap Icons CSS from node_modules to public/css
mix.copy('node_modules/bootstrap-icons/font/bootstrap-icons.css', 'public/css/bootstrap-icons.css')
   // Copy the Bootstrap Icons fonts (woff, woff2, ttf) to public/fonts
   .copyDirectory('node_modules/bootstrap-icons/font/fonts', 'public/fonts');
