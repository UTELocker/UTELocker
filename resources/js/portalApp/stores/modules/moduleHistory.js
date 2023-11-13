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
                const historiesBookings = response.data.data.bookings;
                const transactions = response.data.data.transactions;
                const lockers = [];
                const historiesBooking = historiesBookings.map(history => {
                    if (lockers.indexOf(history.locker_code) === -1) {
                        lockers.push(history.locker_code);
                    }
                    const numBookingOfTransaction = transactions.find(transaction =>
                        transaction.reference === history.transaction_reference
                    ).num_bookings;
                    return {
                        key: history.id,
                        price: history.total_price / numBookingOfTransaction,
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
