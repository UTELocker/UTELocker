import {get} from "../../helpers/api";
import {API} from "../../constants/bookingConstant";

const namespaced = true;

const state = {
    availableLockers: [],
    locker: {},
    lockerSlots: [],
    selectedSlots: [],
}

const getters = {
    availableLockers: state => state.availableLockers,
    lockerSlots: state => state.lockerSlots,
    locker: state => state.locker,
    selectedSlots: state => state.selectedSlots,
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
    },
    setSelectedSlots(state, selectedSlots) {
        state.selectedSlots = selectedSlots;
    }
}

const actions = {
    loadAvailableLockers({ commit }, payload) {
        const { locationIds, startDate, endDate, numberOfSlots } = payload;
        return new Promise((resolve, reject) => {
            get(API.GET_AVAILABLE_LOCKERS({
                'location_ids' : locationIds,
                'start_date' : startDate,
                'end_date' : endDate,
                'number_of_slots' : numberOfSlots,
            })).then(response => {
                const data = response.data.data;
                if (data.length === 0) {
                    reject('No available lockers');
                }
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
                resolve();
            }).catch(error => {
                reject(error);
            });
        })
    },
    loadLockerSlots({ commit }, payload) {
        const { lockerId, startDate, endDate } = payload;

        get(API.GET_LOCKER_SLOTS(lockerId, {
            'start_date' : startDate,
            'end_date' : endDate,
        })).then(response => {
            const data = response.data.data;

            let numberOfSlot = 1;

            const locker = {
                id: data.locker.id,
                image: data.locker.image ? data.locker.image : 'https://via.placeholder.com/150',
                description: data.locker.description,
                location: {
                    address: data.locker.address,
                    latitude: data.locker.latitude,
                    longitude: data.locker.longitude,
                },
            }
            const modules = data.module.map(module => {
                const slots = module.map(slot => {
                    return {
                        ...slot,
                        is_selected: false,
                        number_of_slot: numberOfSlot++,
                    }
                })
                return slots;
            });

            commit('setLocker', locker);
            commit('setLockerSlots', modules);
        });
    },
    setStatusSelectedSlot({ commit, state }, payload) {
        const { slotId } = payload;
        const selectedSlots = [
            ...state.selectedSlots
        ];

        const lockerSlots = state.lockerSlots.map(row => {
            const slots = row.map(slot => {
                if (slot.id === slotId) {

                    if (!slot.is_selected) {
                        selectedSlots.push(slot);
                    } else {
                        selectedSlots.splice(selectedSlots.indexOf(slot), 1);
                    }

                    slot.is_selected = !slot.is_selected;
                }
                return slot;
            });
            return slots;
        });
        commit('setLockerSlots', lockerSlots);
        commit('setSelectedSlots', selectedSlots);
    },
    resetBooking({ commit }) {
        commit('setAvailableLockers', []);
        commit('setLockerSlots', []);
        commit('setLocker', {});
        commit('setSelectedSlots', []);
    }
}

export default {
    namespaced,
    state,
    getters,
    mutations,
    actions
}
