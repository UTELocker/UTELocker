import {get} from "../../helpers/api";
import {API} from "../../constants/bookingConstant";

const namespaced = true;

const state = {
    availableLockers: [],
    locker: {},
    lockerSlots: [],
}

const getters = {
    availableLockers: state => state.availableLockers,
    lockerSlots: state => state.lockerSlots,
    locker: state => state.locker,
}

const mutations = {
    setAvailableLockers(state, availableLockers) {
        state.availableLockers = availableLockers;
    },
    setLockerSlots(state, lockerSlots) {
        state.lockerSlots = lockerSlots;
    },
    setLocker(state, locker) {
        state.locker = locker;
    }
}

const actions = {
    loadAvailableLockers({ commit }, payload) {
        const { locationIds, startDate, endDate } = payload;

        get(API.GET_AVAILABLE_LOCKERS({
            'location_ids' : locationIds,
            'start_date' : startDate,
            'end_date' : endDate,
        })).then(response => {
            const data = response.data.data;
            const availableLockers = data.map(locker => {
                return {
                    id: locker.id,
                    description: locker.description,
                    image: locker.image ? locker.image : 'https://via.placeholder.com/150',
                    address: locker.address,
                    locker_slots_count: locker.locker_slots_count,
                }
            });
            commit('setAvailableLockers', availableLockers);
        });
    },
    loadLockerSlots({ commit }, payload) {
        const { lockerId, startDate, endDate } = payload;

        get(API.GET_LOCKER_SLOTS(lockerId, {
            'start_date' : startDate,
            'end_date' : endDate,
        })).then(response => {
            const data = response.data.data;
            const locker = {
                id: data.locker.id,
                image: data.locker.image ? data.locker.image : 'https://via.placeholder.com/150',
                description: data.locker.description,
            }
            commit('setLocker', locker);
            commit('setLockerSlots', data.module);
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
