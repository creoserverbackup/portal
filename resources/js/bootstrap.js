import {LOGIN_PATH} from "./data/config";

window._ = require('lodash');

////////////////////// VUE \\\\\\\\\\\\\\\\\\\\\\\\
window.Vue = require('vue');

import Vue from "vue";
import locale from 'element-ui/lib/locale'


import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
Vue.use(ElementUI)

export default ({app}) =>{
    locale.i18n((key, value) => app.i18n.t(key, value))

    Vue.component('ElPopover', () => import(/* webpackChunkName: 'element-ui-popover' */ 'element-ui/lib/popover'))
    Vue.component('ElDialog', () => import(/* webpackChunkName: 'element-ui-dialog' */ 'element-ui/lib/dialog'))
    Vue.component('ElAutocomplete', () => import(/* webpackChunkName: 'element-ui-autocomplete' */ 'element-ui/lib/autocomplete'))
    Vue.component('ElInput', () => import(/* webpackChunkName: 'element-ui-input' */ 'element-ui/lib/input'))
    Vue.component('ElButton', () => import(/* webpackChunkName: 'element-ui-input' */ 'element-ui/lib/button'))
    Vue.component('ElSelect', () => import(/* webpackChunkName: 'element-ui-select' */ 'element-ui/lib/select'))
    Vue.component('ElOption', () => import(/* webpackChunkName: 'element-ui-option' */ 'element-ui/lib/option'))
    Vue.component('ElCheckboxGroup', () => import(/* webpackChunkName: 'element-ui-checkbox-group' */ 'element-ui/lib/checkbox-group'))
    Vue.component('ElCheckbox', () => import(/* webpackChunkName: 'element-ui-checkbox' */ 'element-ui/lib/checkbox'))
    Vue.component('ElCheckboxButton', () => import(/* webpackChunkName: 'element-ui-checkbox-button' */ 'element-ui/lib/checkbox-button'))
    Vue.component('ElRadioGroup', () => import(/* webpackChunkName: 'element-ui-radio-group' */ 'element-ui/lib/radio-group'))
    Vue.component('ElRadio', () => import(/* webpackChunkName: 'element-ui-radio' */ 'element-ui/lib/radio'))
    Vue.component('ElForm', () => import(/* webpackChunkName: 'element-ui-form' */ 'element-ui/lib/form'))
    Vue.component('ElFormItem', () => import(/* webpackChunkName: 'element-ui-form-item' */ 'element-ui/lib/form-item'))
    Vue.component('ElUpload', () => import(/* webpackChunkName: 'element-ui-upload' */ 'element-ui/lib/upload'))
    Vue.component('ElRate', () => import(/* webpackChunkName: 'element-ui-rate' */ 'element-ui/lib/rate'))
}

////////////// AXIOS CONTROLLER \\\\\\\\\\\\\\\\\
const axios = require('axios').create({
    baseURL: process.env.MIX_APP_URL,
});


axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    'CREO-LANG': document.querySelector('html').lang,
};

axios.defaults.withCredentials = true;

axios.interceptors.response.use(
    function (successfulReq) {
        if (successfulReq.data === 'blockedUser') {
            window.location.href = LOGIN_PATH
        }
        return successfulReq;
    },
    function (error) {
        if (error.response && error.response.status != "undefined" &&  error.response.status === 401) {
            window.location.href = LOGIN_PATH
        }
        return Promise.reject(error);
    }
);


import VueAxios from 'vue-axios'

window.axios = axios

Vue.use(VueAxios, axios);

///////////////// WEB SOCKETS \\\\\\\\\\\\\\\\\\\\
import Echo from "laravel-echo"

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    wsHost: process.env.MIX_PUSHER_HOST,
    wsPort: process.env.MIX_PUSHER_APP_WS_PORT,
    wssPort: process.env.MIX_PUSHER_APP_WSS_PORT,
    forceTLS: false,
    disableStats: true,
    authEndpoint: '/broadcasting/auth',
    disabledTransports: ['sockjs', 'xhr_polling', 'xhr_streaming'],
    enabledTransports: ['ws', 'wss'],
});

window.Echo.connector.pusher.connection.bind('state_change', function(states) {
    if (states.current === 'disconnected') {
        window.Echo.connector.pusher.connect();
    }
});


// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: process.env.NODE_ENV === 'production',
//     wsHost: window.location.hostname,
//     wssHost: window.location.hostname,
//     wsPort: 6548,
//     wssPort: 443,
//     disableStats: true,
//     authEndpoint: '/broadcasting/auth',
//     disabledTransports: ['sockjs', 'xhr_polling', 'xhr_streaming'],
//     enabledTransports: ['ws', 'wss'],
// });

////////////////////// DAY-JS \\\\\\\\\\\\\\\\\\\\\\\\
window._dayjs = require('dayjs');

// supported locales
require('dayjs/locale/nl');
require('dayjs/locale/en');
require('dayjs/locale/de');
require('dayjs/locale/fr');
require('dayjs/locale/ru');
require('dayjs/locale/es');

window._dayjs.locale(window.lang);

Vue.filter('dayjs', (date, format) => {
    return _dayjs(date).format(format);
});

////////////////////// GLOBAL VUE METHODS \\\\\\\\\\\\\\\\\\\\\\\\
Vue.mixin({
    methods: {
        version: function () {
            return window.version;
        },
        customerId: function () {
            return window.customerId;
        },
        userId: function () {
            return window.user;
        },
        username: function () {
            return window.username;
        },
        lang: function () {
            return window.lang;
        },
        canBuyAccount: function () {
            return window.canBuyAccount;
        }
    }
});

////////////////////// DISABLE ENTER ON INPUT \\\\\\\\\\\\\\\\\\\\\\\\
// Disable submit with keydown enter on inputs without class js-enter
document.querySelectorAll('input:not(.js-enter)').forEach(element => {
    element.addEventListener("keydown", event => {
        if (event.keyCode === 13) {
            event.preventDefault();

            return false;
        }
    });
});
