/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
import '../css/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
import $ from 'jquery';


require('bootstrap');

$(document).ready(function () {
    //Inline DateTimePicker Example
    $('#datetimepicker').datetimepicker({
        format:'d-m-Y H:i',
        inline:true
    });
});

$('#calendar-holder').width(750).css({'margin-left': 'auto', 'margin-right': 'auto'});


$(document).ready(function () {
    $('[data-toogle="popover"]').popover();
});
$("input[type=file]").change(function (e){$(this).next('.custom-file-label').text(e.target.files[0].name);});

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');
require('../css/bootstrap.min.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
