import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

export default new Router({
    mode: 'history',
    base: process.env.BASE_URL,
    routes: [
        {
            path: '/',
            name: 'Index',
            component: import('./components/Index.vue')
        },
        {
            path: '/login',
            name: 'about',
            component: () => import('./components/Login.vue')
        },
    ]
});