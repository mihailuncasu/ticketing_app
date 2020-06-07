// Vue
window.Vue = require('vue');
window.axios = require('axios');
// Imports
import vuetify from '@/plugins/vuetify';
import Auth from '@/api/auth';
import VueRouter from 'vue-router';
import routes from '@/config/routes';
import Vuex from 'vuex';
import store from "@/store/store";
// Plugin use
Vue.use(VueRouter);
Vue.use(Vuex);
// Auth
Vue.prototype.$auth = new Auth(window.user);
// Main component;
Vue.component('dashboard', require('@/views/Dashboard.vue').default);

const router = new VueRouter({
    mode: 'history',
    routes
});

const app = new Vue({
    router,
    vuetify,
    store: store,
    el: '#app',
});