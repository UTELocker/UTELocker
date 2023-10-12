import { createRouter, createWebHistory } from 'vue-router'
import BookingApp from "./booking/BookingApp.vue";
import WalletApp from "./wallet/WalletApp.vue";
import SelectLockerApp from "./booking/SelectLockerApp.vue";

const routes = [
    {
        path: '/portal',
        name: 'portal',
        component: BookingApp,
        children: [
            {
                path: 'booking',
                name: 'booking',
                component: BookingApp,
            },
            {
                path: 'booking/:id',
                name: 'booking-id',
                component: SelectLockerApp,
            }
        ]
    },
    {
        path: '/wallet',
        name: 'wallet',
        component: WalletApp,
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
