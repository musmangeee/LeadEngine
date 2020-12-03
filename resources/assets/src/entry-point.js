// Polyfills
import "core-js/modules/es6.array.fill";
import "core-js/modules/es6.array.iterator";
import "core-js/modules/es6.array.find";
import "core-js/modules/es6.array.from";
import "core-js/modules/es6.object.assign";
import "core-js/modules/es6.object.keys";
import "core-js/modules/es6.promise";
import "core-js/modules/es6.string.includes";
import "core-js/modules/es6.string.starts-with";
import "core-js/modules/es6.symbol";
import "core-js/modules/es6.set";
import "core-js/modules/es7.array.includes";
import "core-js/modules/es7.object.entries";
import "core-js/modules/es7.promise.finally";
import "core-js/modules/es7.symbol.async-iterator";
import { Ability } from "@casl/ability";

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.ability = new Ability();
(window.permissions = [
    {
        module: "Buyer",
        read: { checked: false, disabled: false },
        update: { checked: false, disabled: false },
        create: { checked: false, disabled: false },
        delete: { checked: false, disabled: false }
    },
    {
        module: "Buyer-vertical-channel",
        read: { checked: false, disabled: false },
        update: { checked: false, disabled: false },
        create: { checked: false, disabled: false },
        delete: { checked: false, disabled: false }
    },
    {
        module: "Buyer-panda-channel",
        read: { checked: false, disabled: false },
        update: { checked: false, disabled: false },
        create: { checked: false, disabled: false },
        delete: { checked: false, disabled: false }
    },
    {
        module: "Buyer-plat-channel",
        read: { checked: false, disabled: false },
        update: { checked: false, disabled: false },
        create: { checked: false, disabled: false },
        delete: { checked: false, disabled: false }
    },
    {
        module: "User",
        read: { checked: false, disabled: false },
        update: { checked: false, disabled: false },
        create: { checked: false, disabled: false },
        delete: { checked: false, disabled: false }
    },
    {
        module: "Live-lead",
        read: { checked: true, disabled: false },
        update: { checked: false, disabled: true },
        create: { checked: false, disabled: true },
        delete: { checked: false, disabled: true }
    },
    {
        module: "Lead",
        read: { checked: true, disabled: false },
        update: { checked: false, disabled: false },
        create: { checked: false, disabled: false },
        delete: { checked: false, disabled: false }
    },
    //settings
    {
        module: "Ip-ban",
        read: { checked: false, disabled: false },
        update: { checked: false, disabled: false },
        create: { checked: false, disabled: false },
        delete: { checked: false, disabled: false }
    },
    {
        module: "Redirect",
        read: { checked: true, disabled: false },
        update: { checked: false, disabled: true },
        create: { checked: false, disabled: true },
        delete: { checked: false, disabled: true }
    },
    {
        module: "Redirect-setting",
        read: { checked: false, disabled: false },
        update: { checked: false, disabled: false },
        create: { checked: false, disabled: false },
        delete: { checked: false, disabled: false }
    },
    {
        module: "Fake-lead",
        read: { checked: false, disabled: false },
        update: { checked: false, disabled: false },
        create: { checked: false, disabled: false },
        delete: { checked: false, disabled: false }
    },
    //portfolio
    {
        module: "Portfolio",
        read: { checked: false, disabled: false },
        update: { checked: false, disabled: false },
        create: { checked: false, disabled: false },
        delete: { checked: false, disabled: false }
    },
    {
        module: "Provider",
        read: { checked: false, disabled: false },
        update: { checked: false, disabled: false },
        create: { checked: false, disabled: false },
        delete: { checked: false, disabled: false }
    }

   
]),
    (window.roles = null);

import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.headers.common["Content-Type"] = "application/json";
window.axios.defaults.headers.common["Accept"] = "application/json";


/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
    console.error(
        "CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token"
    );
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

window.datePickerDisplayFormat = "m/d/Y";
window.dateTimePickerDisplayFormat = "m/d/Y h:i K";
window.datePickerSaveFormat = "Y-m-d";
window.dateTimePickerSaveFormat = "Y-m-d H:i";
window.momentDateFormat = "MM/DD/YYYY";
window.momentDateTimeFormat = 'MM/DD/YYYY hh:mm'
// window.momentDateTimeFormat = "MM/DD/YYYY h:mm a";
window.apiDateTimeFormat = "YYYY-MM-DD HH:mm:ss";

/**
 * Load Vue.js app
 */

// eslint-disable-next-line no-undef
require("./main.js");
