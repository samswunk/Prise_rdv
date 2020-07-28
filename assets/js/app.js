/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';
import '../css/bootstrap.min.css';
require ('../fullcalendar/core/main.css');
require ('../fullcalendar/daygrid/main.css');
require ('../fullcalendar/list/main.css');
require ('../fullcalendar/timegrid/main.css');
require ('../fullcalendar/core/main.js');
require ('../fullcalendar/daygrid/main.js');
require ('../fullcalendar/interaction/main.js');
require ('../fullcalendar/list/main.js');
require ('../fullcalendar/timegrid/main.js');

$(".datetimepicker").datetimepicker();

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');