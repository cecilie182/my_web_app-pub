/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

require('bootstrap');

import '@fortawesome/fontawesome-free/css/all.css';
import '@fortawesome/fontawesome-free/js/all'

import AOS from 'aos';
import 'aos/dist/aos.css';
AOS.init();

// language flags
import 'flag-icon-css/css/flag-icon.min.css';

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';
import '../css/header.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
//import $ from 'jquery';