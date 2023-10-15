import {get} from "../../helpers/api";
import {API} from "../../constants/bookingConstant";

const namespaced = true;

const state = {
    locations: [],
}

const getters = {
    locations: state => state.locations,
}

const mutations = {
    setLocations(state, locations) {
        state.locations = locations;
    }
}

const actions = {
    loadLocations({ commit }) {
        get(API.GET_LOCATIONS()).then(response => {
            const data = response.data.data;
            const locations = data.map(location => {
                return {
                    value: location.id,
                    label: location.code + ' - ' + location.description,
                }
            });

            commit('setLocations', locations);
        });
    }
}

export default {
    namespaced,
    state,
    getters,
    mutations,
    actions,
}
