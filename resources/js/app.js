require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import App from './components/App'
import Table from './components/Table'
import About from './components/About'
import store from '../js/store'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'table',
            component: Table
        },
        {
            path: '/about',
            name: 'about',
            component: About,
        },
    ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    router,
    store,
});