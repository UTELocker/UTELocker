import { createRouter, createWebHistory } from 'vue-router'
import Layout from "./components/layouts/Layout.vue";

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

const router =  createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
