import {del, get, post} from "../../helpers/api";
import {API, BOOKING_ACTIVITY_STATUS} from "../../constants/bookingConstant";

const namespaced = true;

const state = {
    locations: [],
    settings: {},
    user: {},
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
    setSettings(state, settings) {
        state.settings = settings;
    },
    setUser(state, user) {
        state.user = user;
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
    loadSettings({ commit }) {
        commit('setSettings', window.settings);
    },
    loadUser({ commit }) {
        commit('setUser', window.user);
    },
    loadBookingActivities({ commit }) {
        get(API.GET_BOOKING_ACTIVITIES()).then(response => {
            const data = response.data.data;
            const bookingActivitiesActive = [];
            const bookingActivitiesNotYet = [];
            data.forEach(activity => {
                const booking = {
                    id: activity.id,
                    slot_code: 'A' + activity.code,
                    title: activity.lockerCode,
                    pin_code: activity.pin_code,
                    status: activity.status,
                    timeRemaining: activity.timeOut,
                    start_date: activity.dateBooked.start.date + ' ' + activity.dateBooked.start.time,
                    end_date: activity.dateBooked.end.date + ' ' + activity.dateBooked.end.time,
                    slot_config: activity.lockerSlotConfig,
                }
                if (activity.status === BOOKING_ACTIVITY_STATUS.ACTIVE) {
                    bookingActivitiesActive.push(booking);
                } else {
                    bookingActivitiesNotYet.push(booking);
                }
            });
            commit('setBookingActivities', bookingActivitiesActive.concat(bookingActivitiesNotYet));
        });
    },
    togglePinCode({ commit }) {
        const isVisiblePinCode = !state.isVisiblePinCode;
        commit('setIsVisiblePinCode', isVisiblePinCode);
    },
    deleteBooking({ commit }, payload) {
        const { bookingId } = payload;
        del(API.DEL_END_BOOKING(bookingId)).then(response => {
            if (response.data.status === 'success') {
                const bookingActivities = state.bookingActivities.filter(booking => booking.id !== bookingId);
                commit('setBookingActivities', bookingActivities);
            }
        }).catch(error => {
            console.log(error);
        });
    },
    changePinCode({ commit }, payload) {
        const { bookingId, pinCode } = payload;
        post(API.POST_CHANGE_PIN_CODE(), {
            'id': bookingId,
            'oldPassword': pinCode,
        }).then(response => {
            if (response.data.status === 'success') {
                const bookingActivities = state.bookingActivities.map(booking => {
                    if (booking.id === bookingId) {
                        booking.pin_code = response.data.data.pin_code;
                    }
                    return booking;
                });
                commit('setBookingActivities', bookingActivities);
            }
        }).catch(error => {
            console.log(error);
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
