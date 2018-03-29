import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

let routes = [
    {
        path:'/',
        component: require('./views/App.vue')
    }
];

const router = new VueRouter({
    routes,
    mode:'history'
    })

export default router