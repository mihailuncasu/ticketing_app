// Vue
window.Vue = require('vue');
window.axios = require('axios');
window.Pusher = require('pusher-js');

window.subdomain = window.location.hostname.split(".")[0];
const token = localStorage.getItem('token');
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '8d5f5ec1947102be68a2',
    cluster: 'eu',
    auth: {
        headers: {
            Authorization: 'Bearer ' + token
        },
    },
});

// Imports
import Echo from "laravel-echo";
import vuetify from '@/plugins/vuetify';
import router from '@/plugins/router';
import Vuex from 'vuex';
import store from "@/store/store";
import App from '@/Main'
import Vuebar from 'vuebar';

// Plugin use
Vue.use(Vuex);
Vue.use(Vuebar);
Vue.use(require('vue-moment'));

export const app = new Vue({
    store: store,
    router,
    vuetify,
    render: h => h(App),
    el: '#app',
});