/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from 'vue'
import VueRouter from 'vue-router'
import Node from './components/Node.vue'
import Search from './components/Search.vue'
import Main from './Main.vue'

Vue.use(VueRouter);


/**
 * Routing
 */

const routes = [
    {path: '/node', component: Node},
    {path: '/search', component: Search}
];

const router = new VueRouter({
    routes // raccourci pour `routes: routes`
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    router,
    render: h => h(Main)
}).$mount('#app');
