import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        loading: false,
    },
    mutations: {
        LOADING: (state, loading) => {
            state.loading = loading;
        }
    },
    actions: {
        Loading({ commit }, loading) {
            commit('LOADING', loading);
        }
    }
});

export default store