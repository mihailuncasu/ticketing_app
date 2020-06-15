// Vue
window.Vue = require('vue');
window.axios = require('axios');

// Imports
import vuetify from '@/plugins/vuetify';
import router from '@/plugins/router';
import Vuex from 'vuex';
import store from "@/store/store";
import App from '@/Main'

// Plugin use
Vue.use(Vuex);
Vue.use(require('vue-moment'));

export const app = new Vue({
    router,
    vuetify,
    store: store,
    render: h => h(App),
    el: '#app',
});