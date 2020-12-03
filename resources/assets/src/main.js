import Vue from "vue";
import Vuex from "vuex";
import App from "./App";
import router from "./router";
import "es6-promise/auto";

import BootstrapVue from "bootstrap-vue";
import VueSweetalert2 from "vue-sweetalert2";
import BlockUI from "vue-blockui";
import VueClipboard from "vue-clipboard2";
import Toasted from "vue-toasted";

import globals from "./globals";
import store from "./store";
import Popper from "popper.js";
import "nprogress/nprogress.css";
import Notifications from "vue-notification";
import Snotify from "vue-snotify";
import VueMoment from "vue-moment";
import moment from "moment-timezone";
import VueCurrencyFilter from "vue-currency-filter";

import VueFlatPickr from "vue-flatpickr-component";
import "flatpickr/dist/themes/material_red.css";

import "./bugsnag";
import bugsnagClient from "./bugsnag";

bugsnagClient.user = {
    id: localStorage.getItem("user_id"),
    name:
        localStorage.getItem("first_name") +
        " " +
        localStorage.getItem("last_name"),
    email: localStorage.getItem("email"),
    timezone: localStorage.getItem("timezone")
};

if (process.env.NODE_ENV == "production") {
    //Vue.use(VueAnalytics, {
    //    id: '',
    //    router
    //})
}

// Required to enable animations on dropdowns/tooltips/popovers
Popper.Defaults.modifiers.computeStyle.gpuAcceleration = true;

Date.prototype.addHours = function(h) {
    var copiedDate = new Date(this.getTime());
    copiedDate.setHours(copiedDate.getHours() + h);
    return copiedDate;
};

Vue.config.productionTip = false;

Vue.use(Vuex);
Vue.use(BootstrapVue);
Vue.use(VueSweetalert2);
Vue.use(BlockUI);
Vue.use(Toasted);
Vue.use(Notifications);
Vue.use(Snotify, {
    toast: {
        timeout: 6000,
        position: "rightTop",
        pauseOnHover: false
    }
});
//Vue.use(require('vue-moment'));
Vue.use(VueMoment, {
    moment
});
moment.tz.setDefault(localStorage.getItem("timezone"));

VueClipboard.config.autoSetContainer = true; // add this line
Vue.use(VueClipboard);

Vue.use(VueCurrencyFilter, {
    symbol: "$",
    thousandsSeparator: ",",
    fractionCount: 2,
    fractionSeparator: ".",
    symbolPosition: "front",
    symbolSpacing: false
});
Vue.use(VueFlatPickr);

// Global RTL flag
Vue.mixin({
    data: globals
});

// Empty Filter
Vue.filter("handleEmpty", value => {
    if (value === "" || value === null || value == undefined) {
        return "-";
    }
    return value;
});

// numeric-only force input
Vue.directive("numeric-only", {
    bind(el) {
        el.addEventListener("keydown", e => {
            if (
                [46, 8, 9, 27, 13, 110, 190].indexOf(e.keyCode) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode === 65 && e.ctrlKey === true) ||
                // Allow: Ctrl+C
                (e.keyCode === 67 && e.ctrlKey === true) ||
                // Allow: Ctrl+X
                (e.keyCode === 88 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)
            ) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if (
                (e.shiftKey || e.keyCode < 48 || e.keyCode > 57) &&
                (e.keyCode < 96 || e.keyCode > 105)
            ) {
                e.preventDefault();
            }
        });
    }
});

//Pre-loading timezone and country
window.axios.get("/api/helpers/get-timezones").then(response => {
    Vue.prototype.$_timezones = response.data.map(timezone => {
        let offset = timezone.offset / 3600;
        if (offset > 0) {
            offset = `+${offset}`;
        }
        return {
            text: `[${offset}] ${timezone.id}`,
            value: timezone.id
        };
    });
});

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount("#app");
