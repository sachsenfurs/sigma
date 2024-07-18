import _ from 'lodash';
window._ = _;


/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
import getDocumentElement from "@popperjs/core/lib/dom-utils/getDocumentElement.js";
import {hide} from "@popperjs/core";
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });


// bootstrap sidebar
/* global bootstrap: false */
(function () {
    'use strict'
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl)
    })

    let touchStart = null;
    document.querySelector("body").addEventListener("touchstart", function(event) {
        touchStart = event.touches[0];
    });
    document.querySelector("main").addEventListener("touchstart", function(event) {
        if(isNavVisible()) {
            hideNav();
            event.preventDefault();
        }
    });
    document.querySelector("body").addEventListener("touchend", function(event) {
        touchStart = null;
    });
    document.querySelector("body").addEventListener("touchmove", function (event) {
        if(touchStart === null)
            return;

        let touch = event.touches[0];
        if((touchStart.clientX - touch.clientX) > 45 && Math.abs(touchStart.clientY - touch.clientY) < 30)
            hideNav();

        if((touch.clientX - touchStart.clientX) > 100 && Math.abs(touchStart.clientY - touch.clientY) < 30)
            showNav();
    })

    function hideNav() {
        bootstrap.Collapse.getInstance(document.querySelector(".navbar2"))?.hide();
    }
    function showNav() {
        bootstrap.Collapse.getInstance(document.querySelector(".navbar2"))?.show() || new bootstrap.Collapse(document.querySelector(".navbar2")).show();
    }
    function isNavVisible() {
        return bootstrap.Collapse.getInstance(document.querySelector(".navbar2"))?._isShown();
    }
})()
