import {del, get, post, put} from "../../helpers/api";
import {API, BOOKING_ACTIVITY_STATUS} from "../../constants/bookingConstant";
import dayjs from "dayjs";
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
    loadBookingActivities({ commit }, payload) {
        return new Promise((resolve, reject) => {
            get(API.GET_BOOKING_ACTIVITIES(payload)).then(response => {
                const data = response.data.data;
                const bookingActivitiesActive = [];
                const bookingActivitiesNotYet = [];
                const bookingActivitiesExpired = [];
                data.forEach(activity => {
                    const booking = {
                        id: activity.id,
                        slot_code: activity.code,
                        title: activity.lockerCode,
                        pin_code: activity.pin_code,
                        status: activity.status,
                        start_date: activity.dateBooked.start.date + ' ' + activity.dateBooked.start.time,
                        end_date: activity.dateBooked.end.date + ' ' + activity.dateBooked.end.time,
                        slot_config: activity.lockerSlotConfig,
                        address: activity.address,
                        location: activity.location,
                        lockerImage: activity.lockerImage,
                        lockerId: activity.lockerId,
                        lockerSlotId: activity.lockerSlotId,
                        bufferTime: activity.bufferTime,
                        pricePerHours: activity.pricePerHours,
                    }
                    if (activity.status === BOOKING_ACTIVITY_STATUS.ACTIVE) {
                        bookingActivitiesActive.push(booking);
                    } else if (activity.status === BOOKING_ACTIVITY_STATUS.NOT_YET) {
                        bookingActivitiesNotYet.push(booking);
                    } else {
                        bookingActivitiesExpired.push(booking);
                    }
                });
                commit('setBookingActivities', bookingActivitiesExpired.concat(bookingActivitiesActive).concat(bookingActivitiesNotYet));
                resolve();
            });
        });
    },
    toggleIsVisibleBalance({ commit, state }) {
        commit('setIsVisibleBalance', !state.isVisibleBalance);
    },
    deleteBooking({ commit }, payload) {
        const { bookingId } = payload;
        return new Promise((resolve, reject) => {
            del(API.DEL_END_BOOKING(bookingId)).then(response => {
                if (response.data.status === 'success') {
                    const bookingActivities = state.bookingActivities.filter(booking => booking.id !== bookingId);
                    commit('setBookingActivities', bookingActivities);
                }
                resolve();
            }).catch(error => {
                reject(error);
            });
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
            //
        });
    },
    extendTimeBooking({ commit }, payload) {
        const {extendTime, bookingId} = payload;
        return new Promise((resolve, reject) => {
            put(API.PUT_EXTEND_TIME(bookingId), {
                extend_time: extendTime,
            }).then(() => {
                const bookings = state.bookingActivities.map(booking => {
                    if (booking.id === bookingId) {
                        booking.end_date = dayjs(booking.end_date).add(extendTime, 'minute').format('YYYY-MM-DD HH:mm');
                    }
                    return booking;
                });
                commit('setBookingActivities', bookings);
                resolve();
            }).catch((e) => {
                reject(e);
            });
        });
    },
    updateUser({ commit }, payload) {
        const { user } = payload;
        return new Promise((resolve, reject) => {
            put(API.PUT_UPDATE_USER(), user).then(response => {
                if (response.data.status === 'success') {
                    commit('setUser', response.data.data);
                    resolve();
                } else {
                    reject(response);
                }
            }).catch(error => {
                reject(error);
            });
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
