const namespaced = true;

const state = {
    isAccept: false,
}

const getters = {
    isAccept: state => state.isAccept,
}

const mutations = {
    setIsAccept(state, isAccept) {
        state.isAccept = isAccept;
    }
}

const actions = {
    accept({ commit }) {
        commit('setIsAccept', true);
    }
}

export default {
    namespaced,
    state,
    getters,
    mutations,
    actions,
}
