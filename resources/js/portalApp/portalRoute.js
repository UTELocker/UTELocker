import { createRouter, createWebHistory } from 'vue-router'
import Layout from "./components/layouts/Layout.vue";
import BookingApp from "./booking/BookingApp.vue";

const routes = [
    {
        path: '/portal',
        name: 'portal',
        component: Layout,
    },
    {
        path: '/portal/wallet',
        name: 'wallet',
        component: Layout,
    }
];

export default createRouter({
    history: createWebHistory(),
    fallback: false,
    routes,
    scrollBehavior: to => {
        if (to.hash) {
            return { el: to.hash, top: 80, behavior: 'auto' };
        }
    },
});
