import { createStore } from 'vuex';
import moduleBase from './modules/moduleBase';
import moduleBooking from "./modules/moduleBooking.js";
import moduleHistory from "./modules/moduleHistory.js";
import moduleNotification from "./modules/moduleNotification.js";

const store = createStore({
    modules: {
        moduleBase,
        moduleBooking,
        moduleHistory,
        moduleNotification,
    }
});

export default store;
