/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue'
import {mapGetters, mapActions} from 'vuex';

import VueSidebarMenu from 'vue-sidebar-menu'
import 'vue-sidebar-menu/dist/vue-sidebar-menu.css'
import VueGoodTablePlugin from 'vue-good-table';
import 'vue-good-table/dist/vue-good-table.css'

import store from './store/store';

Vue.use(VueSidebarMenu);
Vue.use(VueGoodTablePlugin);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('my-menu', require('./components/MyMenu.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    store,
    computed: {
        ...mapGetters({
            collapsed: 'collapsed',
            isOnMobile: 'onMobile'
        })
    },
    methods: {
        ...mapActions({
            setCollapsed: 'setCollapsed',
            setOnMobile: 'setOnMobile',
            setIsAdmin: 'setIsAdmin'
        }),
        isAdmin(next) {
            this.setIsAdmin(next);
            return '';
        }
    }
});
