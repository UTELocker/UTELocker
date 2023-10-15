import {get} from "../../helpers/api";
import {API} from "../../constants/bookingConstant";

const namespaced = true;

const state = {
    availableLockers: [],
}

const getters = {
    availableLockers: state => state.availableLockers,
}

const mutations = {
    setAvailableLockers(state, availableLockers) {
        state.availableLockers = availableLockers;
    }
}

const actions = {
    loadAvailableLockers({ commit }, payload) {
        const { locationIds, startDate, endDate } = payload;
        console.log('loadAvailableLockers', locationIds, startDate, endDate);
        get(API.GET_AVAILABLE_LOCKERS({locationIds, startDate, endDate})).then(response => {
            const data = response.data.data;
            console.log('loadAvailableLockers', data);
            // commit('setAvailableLockers', availableLockers);
        });
    }
}

export default {
    namespaced,
    state,
    getters,
    mutations,
    actions
}
