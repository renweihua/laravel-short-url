const dotenvExpand = require('dotenv-expand');
dotenvExpand(require('dotenv').config({ path: '../../.env'/*, debug: true*/}));

const mix = require('laravel-mix');
// require('laravel-mix-merge-manifest');

// mix.setPublicPath('../../public');

mix.js(__dirname + '/Resources/assets/js/app.js', 'public/js/app.js')
    .sass( __dirname + '/Resources/assets/sass/app.scss', 'public/css/app.css');

if (mix.inProduction()) {
    mix.version();
}
