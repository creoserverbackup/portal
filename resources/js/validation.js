import Vue from 'vue'
const validationPassword = require('./components/validationPassword').default;

Vue.component('validationpassword', validationPassword)

new Vue({
    el: '#validation',
});
