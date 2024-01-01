
const $ = require('jquery');
global.$ = global.jQuery = $;
import './bootstrap';

import './js/config.js';
import '@popperjs/core';
import 'bootstrap';
import '@fortawesome/fontawesome-free/js/all.min.js';
import './custom/toast';
import 'Hinclude/hinclude'

/*
document.addEventListener('turbo:load', function (e) {
 // this enables bootstrap tooltips globally
 let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
 let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new Tooltip(tooltipTriggerEl)
 });
});

 */
var navbarStyle = window.config.config.phoenixNavbarVerticalStyle;
if (navbarStyle && navbarStyle !== 'transparent') {
 document.querySelector('body').classList.add(`navbar-${navbarStyle}`);
}