import { createStore } from 'vuex';
import moduleBase from './modules/moduleBase';
import moduleBooking from "./modules/moduleBooking.js";

const store = createStore({
    modules: {
        moduleBase,
        moduleBooking
    }
});

export default store;
