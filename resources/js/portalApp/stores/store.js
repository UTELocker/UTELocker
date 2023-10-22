import { createStore } from 'vuex';
import moduleBase from './modules/moduleBase';
import moduleBooking from "./modules/moduleBooking.js";
import moduleHistory from "./modules/moduleHistory.js";

const store = createStore({
    modules: {
        moduleBase,
        moduleBooking,
        moduleHistory,
    }
});

export default store;
