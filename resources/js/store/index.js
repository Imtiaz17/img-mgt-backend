import Vuex from 'vuex';
import Vue from 'vue';
import Cookies from 'js-cookie'
import axios from 'axios';
import router from '../components/router'
import { getToken, setToken, removeToken } from '../utils/auth';
Vue.use(Vuex)
export default new Vuex.Store({
    state: {
        user: null,
        token: getToken(),
        id: null,
        drawer: true,
    },
    getters: {
        id: state => state.id,
        user: state => state.user,
        token: state => state.token,
        auth: state => state.token !== null
    },
    mutations: {
        SET_TOKEN(state, token) {
            state.token = token
        },
        FETCH_USER_SUCCESS(state, user) {
            state.user = user
            state.id = user.id
            state.drawer = true
        },
        Remove_User(state) {
            state.user = null
            state.token = null
            state.id = null
            state.drawer = null
        },
        SET_DRAWER(state, payload) {
            state.drawer = payload;
        },

    },
    actions: {
        getInfo({ commit, state }) {
            axios.post('api/auth/me')
                .then((res) => {
                    commit('FETCH_USER_SUCCESS', res.data)
                })
        },

        saveToken({ commit, dispatch }, { token }) {
            commit('SET_TOKEN', token)
            Cookies.set('token', token)
            axios.post('api/auth/me')
                .then((res) => {
                    commit('FETCH_USER_SUCCESS', res.data)
                })
        },
        logout({ commit, state }) {
            return new Promise((resolve, reject) => {
                axios.post('/api/auth/logout')
                    .then(() => {
                        Cookies.remove('token');
                        commit('Remove_User')
                        router.push('/')

                    })
            })
        },
    }
});
