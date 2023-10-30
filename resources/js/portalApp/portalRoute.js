import { createRouter, createWebHistory } from 'vue-router'
import BookingApp from "./booking/BookingApp.vue";
import WalletApp from "./wallet/WalletApp.vue";
import SelectLockerApp from "./booking/SelectLockerApp.vue";
import OverviewApp from "./overview/OverviewApp.vue";
import ConfirmBookingApp from "./booking/ConfirmBookingApp.vue";
import LocationApp from "./location/LocationApp.vue";
import HistoryApp from "./history/HistoryApp.vue";
import TopUp from "./wallet/TopUp/TopUp.vue";
import Transaction from "./wallet/Transaction/Transaction.vue";

const routes = [
    {
        path: '/portal',
        name: 'portal',
        component: OverviewApp,
    },
    {
        path: '/portal/:id',
        name: 'portal.booking',
        component: OverviewApp,
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
        path: '/portal/booking/confirm',
        name: 'booking.confirm',
        component: ConfirmBookingApp,
    },
    {
        path: '/portal/locations',
        name: 'locations',
        component: LocationApp,
    },
    {
        path: '/portal/histories',
        name: 'histories',
        component: HistoryApp,
    },
    {
        path: '/wallet',
        name: 'wallet',
        component: WalletApp,
    },
    {
        path: '/wallet/topup',
        name: 'wallet.topup',
        component: TopUp,
    },
    {
        path: '/wallet/transactions',
        name: 'wallet.transactions',
        component: Transaction,
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
