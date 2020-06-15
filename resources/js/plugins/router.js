import routes from '@/config/routes';
import VueRouter from 'vue-router';
window.Vue = require('vue');

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes
});

export default router;