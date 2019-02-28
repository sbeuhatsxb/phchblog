/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
// require('../../intern/css_save/app.css');
// require('../../intern/css_save/page.css');
require('../css/global.scss');
require('../js/matchHeight.js');
require('../js/navBar.js');
require('bootstrap');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require('jquery');


$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});