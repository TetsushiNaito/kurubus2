/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import VueCookies from 'vue-cookies';
import VueAxios from 'vue-axios';
import axios from 'axios';
import debounce from 'lodash.debounce';

import HeaderComponent from "./components/HeaderComponent";
import TimetableListComponent from "./components/TimetableListComponent";
import TimetableWaitComponent from "./components/TimetableWaitComponent";
import DigitalClockComponent from "./components/DigitalClockComponent";
import InputPoleNameComponent from "./components/InputPoleNameComponent";
import TimetableInitComponent from "./components/TimetableInitComponent";
import TimetableSorryComponent from "./components/TimetableSorryComponent";
import TimetableErrorComponent from "./components/TimetableErrorComponent";

import Vue from 'vue';

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('header-component', HeaderComponent);
Vue.component('timetable-wait-component', TimetableWaitComponent);
Vue.component('timetable-list-component', TimetableListComponent);
Vue.component('timetable-init-component', TimetableInitComponent);
Vue.component('timetable-sorry-component', TimetableSorryComponent);
Vue.component('timetable-error-component', TimetableErrorComponent);
Vue.component('digital-clock', DigitalClockComponent);
Vue.component('input-pole-name', InputPoleNameComponent);

Vue.use(VueCookies);
Vue.use(VueAxios, axios);
Vue.use(debounce);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
