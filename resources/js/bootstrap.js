import $ from 'jquery';
window.$ = $;

import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

function defineJQueryPlugin(plugin) {
    const name = plugin.NAME;
    const JQUERY_NO_CONFLICT = $.fn[name];
    $.fn[name] = plugin.jQueryInterface;
    $.fn[name].Constructor = plugin;
    $.fn[name].noConflict = () => {
        $.fn[name] = JQUERY_NO_CONFLICT;
        return plugin.jQueryInterface;
    }
}

defineJQueryPlugin(bootstrap.Modal);
defineJQueryPlugin(bootstrap.Tooltip);
defineJQueryPlugin(bootstrap.Popover);

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

// import axios from 'axios';
// window.axios = axios;
//
// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

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
        touchStart.timeStamp = event.timeStamp;
    });
    document.querySelector(".main-col").addEventListener("touchstart", function(event) {
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
        if(window.innerWidth >= 992)
            return;
        if(event.timeStamp - touchStart.timeStamp > 80)
            return;
        if(touchStart.clientX > 60)
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
