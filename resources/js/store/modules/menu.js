const state = {
    collapsed: true,
    isOnMobile: false,
    isAdmin: false,
};

const mutations = {
    setCollapsed(state, next) {
        state.collapsed = next;
    },
    setOnMobile(state, next) {
        state.isOnMobile = next;
    },
    setIsAdmin(state, next) {
        state.isAdmin = next;
    }
};

const actions = {
    setCollapsed({commit}, next){
        commit('setCollapsed', next);
    },
    setOnMobile({commit}, next){
        commit('setOnMobile', next);
    },
    setIsAdmin({commit}, next){
        commit('setIsAdmin', next);
    }
};

const getters = {
    collapsed (state) {
        return state.collapsed;
    },
    onMobile (state) {
        return state.isOnMobile;
    },
    isAdmin (state) {
        return state.isAdmin;
    }
};

export default {
    state,
    mutations,
    actions,
    getters
}
