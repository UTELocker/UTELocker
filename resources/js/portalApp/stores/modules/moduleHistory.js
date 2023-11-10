import {get} from "../../helpers/api";
import {API} from "../../constants/historyConstant";

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
                     if (lockers.indexOf(history.locker_code) === -1) {
                        lockers.push(history.locker_code);
                    }
                    return {
                        key: history.id,
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
