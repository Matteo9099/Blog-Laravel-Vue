window.Vue = require('vue');
import "bootstrap/dist/css/bootstrap.min.css"

import Vue from 'vue';
//importo component App
import App from './views/App';

const app = new Vue({
    el: '#root',
    render: h => h(App)
});

import "bootstrap/dist/js/bootstrap.js"