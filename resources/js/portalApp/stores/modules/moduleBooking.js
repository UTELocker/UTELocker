import {get} from "../../helpers/api";
import {API, SLOT_TYPE} from "../../constants/bookingConstant";

const namespaced = true;

const state = {
    availableLockers: [],
    locker: {},
    lockerSlots: [],
    selectedSlots: [],
    configNumberSlot: []
}

const getters = {
    availableLockers: state => state.availableLockers,
    lockerSlots: state => state.lockerSlots,
    locker: state => state.locker,
    selectedSlots: state => state.selectedSlots,
    configNumberSlot: state => state.configNumberSlot,
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
    },
    setConfigNumberSlot(state, configNumberSlot) {
        state.configNumberSlot = configNumberSlot;
    },
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
                        description: locker.description ? locker.description : 'Tủ đồ',
                        image: locker.image ? locker.image : 'https://via.placeholder.com/150',
                        address: locker.address,
                        locker_slots_count: locker.locker_slots_count,
                        code: locker.code,
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

        return new Promise((resolve, reject) => {
            get(API.GET_LOCKER_SLOTS(lockerId, {
                'start_date' : startDate,
                'end_date' : endDate,
            })).then(response => {
                if (response.data.status === 'success') {
                    const data = response.data.data;
                    let slotCPU = {};
                    data.module.forEach(module => {
                        module.forEach(slot => {
                            if (slot.type === SLOT_TYPE.CPU) {
                                slotCPU = slot;
                            }
                        })
                    });
                    const configLocker = JSON.parse(slotCPU.config ? slotCPU.config : `
                        {
                            "price": 10000,
                            "prefix": ""
                        }
                    `);

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
                        code: data.locker.code,
                    }
                    const modules = data.module.map(module => {
                        const slots = module.map(slot => {
                            const configSlot = JSON.parse(slot.config ? slot.config : '{}');
                            configSlot.price = configSlot.price ? configSlot.price : configLocker.price;
                            return {
                                ...slot,
                                is_selected: false,
                                number_of_slot: slot.type === SLOT_TYPE.SLOT ? configLocker.prefix + numberOfSlot++ : null,
                                config: configSlot,
                            }
                        })
                        return slots;
                    });

                    const configNumberSlot = data.configNumberSlot;

                    commit('setLocker', locker);
                    commit('setLockerSlots', modules);
                    commit('setConfigNumberSlot', configNumberSlot);
                }
                if (response.data.status === 'fail') {
                    if (response.data.error_name === 'locker_not_found' || response.data.error_name === 'exceeding_limit_time') {
                        window.location.href = 'portal/404';
                    }
                }
                resolve();
            }).catch(error => {
                reject(error);
            });
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
    },
}

export default {
    namespaced,
    state,
    getters,
    mutations,
    actions
}
