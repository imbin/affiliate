import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        loading: false,
        userLogined: false,
        userName: null,
    },
    mutations: {
        LOADING: (state, loading) => {
            state.loading = loading;
        },
        LOGINED: (state, data) => {
            state.userLogined = data.logined;
            state.userName = data.userName;
        },
        USERINFO: (state, data) => {
            state.userInfo = data;
        },
    },
    actions: {
        Loading({ commit }, loading) {
            commit('LOADING', loading);
        },
        Logined({ commit }, data) {
            commit('LOGINED', data);
        },
        UserInfo({ commit }, data) {
            commit('USERINFO', data);
        },
    }
});

export default store