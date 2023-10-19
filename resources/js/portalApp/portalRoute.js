import { createRouter, createWebHistory } from 'vue-router'
import BookingApp from "./booking/BookingApp.vue";
import WalletApp from "./wallet/WalletApp.vue";
import SelectLockerApp from "./booking/SelectLockerApp.vue";
import OverviewApp from "./overview/OverviewApp.vue";
import ConfirmBookingApp from "./booking/ConfirmBookingApp.vue";

const routes = [
    {
        path: '/portal',
        name: 'portal',
        component: OverviewApp,
        children: [
            {
                path: 'booking',
                name: 'booking',
                component: BookingApp,
            },
            {
                path: 'booking/:id',
                name: 'booking.locker',
                component: SelectLockerApp,
            },
            {
                path: 'booking/confirm',
                name: 'booking.confirm',
                component: ConfirmBookingApp,
            }
        ]
    },
    {
        path: '/portal/booking',
        name: 'booking',
        component: BookingApp,
    },
    {
        path: '/portal/booking/:id',
        name: 'booking.locker',
        component: SelectLockerApp,
    },
    {
        path: '/wallet',
        name: 'wallet',
        component: WalletApp,
    },
    {
        path: '/portal/booking/confirm',
        name: 'booking.confirm',
        component: ConfirmBookingApp,
    }
];

export default createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior: to => {
        if (to.hash) {
            return { el: to.hash, top: 80, behavior: 'auto' };
        }
    },
});
