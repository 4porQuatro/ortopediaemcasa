
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

//External Scripts
require('./libs/slickApplication.js');

window.Vue = require('vue');

var VueScrollTo = require('vue-scrollto');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue Configs
Vue.config.devtools = true;
Vue.config.productionTip = true;

//Vue Utilities
Vue.use(VueScrollTo, {duration: 1500});

//Vue Components
Vue.component('modal', require('./components/Modal.vue'));
Vue.component('tabs', require('./components/Tabs.vue'));
Vue.component('tab', require('./components/Tab.vue'));

const app = new Vue({
    el: '#app',

    data: {
        showSchedule: false,
        showReverse: false
    },

    created(){
        console.log('hello!!');  
    },

    methods: {

        toggleSchedule() {
            this.showSchedule = !this.showSchedule; //or ^=
        }

    }
});


