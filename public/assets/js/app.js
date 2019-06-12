

//要先开启 encore js/css 服务器 
//npm run dev-server

/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

var $ = require('Jquery') ;


// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');


//
console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
//如果这里要用 select2 需要 用yard add select2 安装它
require('select2');
$('select').select2();