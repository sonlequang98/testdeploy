let mix = require('laravel-mix');

if (process.env.section){
    require(`./webpack.${process.env.section}.mix.js`);
}
