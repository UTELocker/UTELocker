import {get} from "../../helpers/api";
import {API} from "../../constants/bookingConstant";

const namespaced = true;

const state = {
    locations: [],
    bookingActivities: [],
}

const getters = {
    locations: state => state.locations,
    bookingActivities: state => state.bookingActivities,
}

const mutations = {
    setLocations(state, locations) {
        state.locations = locations;
    },
    setBookingActivities(state, bookingActivities) {
        state.bookingActivities = bookingActivities;
    },
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
    },
    loadBookingActivities({ commit }) {
        get(API.GET_BOOKING_ACTIVITIES()).then(response => {
            const data = response.data.data;
            // const bookingActivities = data.map(activity => {
            //     return {
            //         id: activity.id,
            //         pin_code: activity.pin_code,
            //         status: activity.status,
            //         start_date: activity.start_date,
            //         end_date: activity.end_date,
            //     }
            // });
            const bookingActivities = [
                {
                    id: 1,
                    slot_code: 'A1',
                    title: 'Locker 1',
                    pin_code: '123456',
                    status: 0,
                    start_date: '2021-01-01 00:00:00',
                    end_date: '2021-01-01 00:00:00',
                },
                {
                    id: 2,
                    slot_code: 'A2',
                    title: 'Locker 2',
                    pin_code: '123456',
                    status: 1,
                    start_date: '2021-01-01 00:00:00',
                    end_date: '2021-01-01 00:00:00',
                }
            ]

            commit('setBookingActivities', bookingActivities);
        });
    },
    togglePinCode({ commit }) {
        const isVisiblePinCode = !state.isVisiblePinCode;
        commit('setIsVisiblePinCode', isVisiblePinCode);
    }
}

export default {
    namespaced,
    state,
    getters,
    mutations,
    actions,
}
