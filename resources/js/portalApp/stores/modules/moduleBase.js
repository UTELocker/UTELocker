import {del, get, post} from "../../helpers/api";
import {API, BOOKING_ACTIVITY_STATUS} from "../../constants/bookingConstant";
import {WALLET_API} from "../../constants/walletConstant";

const namespaced = true;

const state = {
    locations: [],
    settings: {},
    user: {},
    wallet: {},
    bookingActivities: [],
    isVisibleBalance: false,
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
    setWallet(state, wallet) {
        state.wallet = wallet;
    },
    setBookingActivities(state, bookingActivities) {
        state.bookingActivities = bookingActivities;
    },
    setIsVisibleBalance(state, isVisibleBalance) {
        state.isVisibleBalance = isVisibleBalance;
    }
}

const actions = {
    loadLocations({ commit }) {
        return new Promise((resolve, reject) => {
            get(API.GET_LOCATIONS()).then(response => {
                const data = response.data.data;
                const locations = data.map(location => {
                    return {
                        value: location.id,
                        code: location.code,
                        label: location.code + ' - ' + location.description,
                        latitude: location.latitude,
                        longitude: location.longitude,
                        lockers: location.lockers.map(locker => {
                            return {
                                id: locker.id,
                                image: locker.image,
                                status: locker.status,
                                code: locker.code,
                            }
                        }),
                    }
                });
                commit('setLocations', locations);
                resolve(locations);
            }).catch(error => {
                reject(error);
            });
        });
    },
    loadSettings({ commit }) {
        commit('setSettings', window.settings);
    },
    loadUser({ commit }) {
        commit('setUser', window.user);
    },
    loadWallet({ commit }) {
        get(WALLET_API.GET_WALLET()).then(response => {
            const data = response.data.data;
            const wallet = {
                balance: data.balance,
                promotion_balance: data.promotion_balance,
            }

            commit('setWallet', wallet);
        });
    },
    loadBookingActivities({ commit }) {
        return new Promise((resolve, reject) => {
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
                resolve();
            });
        });
    },
    toggleIsVisibleBalance({ commit, state }) {
        commit('setIsVisibleBalance', !state.isVisibleBalance);
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
    },
}

export default {
    namespaced,
    state,
    getters,
    mutations,
    actions,
}
