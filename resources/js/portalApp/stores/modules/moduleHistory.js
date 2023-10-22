import {get} from "../../helpers/api";
import {API} from "../../constants/hisgoryConstant";

const namespaced = true;

const state = {
    historiesBooking: [],
    lockersHistories: [],
}

const getters = {
    historiesBooking: state => state.historiesBooking,
    lockersHistories: state => state.lockersHistories,
}

const mutations = {
    setHistoriesBooking(state, historiesBooking) {
        state.historiesBooking = historiesBooking;
    },
    setLockersHistories(state, lockersHistories) {
        state.lockersHistories = lockersHistories;
    }
}

const actions = {
    loadHistoriesBooking({ commit }) {
        return new Promise((resolve, reject) => {
            get(API.GET_HISTORIES_BOOKING()).then(response => {
                const res = response.data.data;
                const lockers = [];
                const historiesBooking = res.map(history => {
                    const startTime = new Date(history.start_date);
                    const endTime = new Date(history.end_date);
                    const durationTime = (endTime.getTime() - startTime.getTime()) / (1000 * 3600);
                    const totalPrice = (history.config?.price_of_hour ?? 10000) * durationTime;
                    if (lockers.indexOf(history.locker_code) === -1) {
                        lockers.push(history.locker_code);
                    }
                    return {
                        key: history.id,
                        total_price: totalPrice,
                        ...history,
                    }
                });
                commit('setLockersHistories', lockers);
                commit('setHistoriesBooking', historiesBooking);
                resolve();
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
