
const $ = require('jquery');
global.$ = global.jQuery = $;
import './bootstrap';

//import '@popperjs/core/dist/umd/popper.min.js';
import * as bootstrap from 'bootstrap'
//import Tooltip from 'bootstrap/js/dist/tooltip';


//import 'Hinclude/hinclude'
import './js/config.js';
//import './phenixVendor/simplebar/simplebar.min.js';



import '@popperjs/core';
import 'bootstrap';
//import './phenixVendor/anchorjs/anchor.min.js';
//import './phenixVendor/is/is.min.js';
//import './phenixVendor/fontawesome/all.min';
import '@fortawesome/fontawesome-free/js/all.min.js';
import './phenixVendor/lodash/lodash.min.js';
//import 'https://polyfill.io/v3/polyfill.min.js?features=window.scroll';
//import './phenixVendor/list.js/list.min.js';
//import './phenixVendor/feather-icons/feather.min.js';
//import './phenixVendor/dayjs/dayjs.min.js';
import './js/phoenix.js';
//import './phenixVendor/leaflet/leaflet';
import './custom/toast'


//import 'anchor.min.js';

//import './custom/all.min.js';
//import './custom/feather-icons/feather.min.js';


//import './js/utils.js';
//import './js/crm-dashboard.js';

//import './js/themeColors.js';
import 'Hinclude/hinclude'

document.addEventListener('turbo:load', function (e) {
 // this enables bootstrap tooltips globally
 let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
 let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new Tooltip(tooltipTriggerEl)
 });
});