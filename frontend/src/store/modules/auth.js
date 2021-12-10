import {http} from "appmakers-vue/services/http-service";
import router from '../../router'

export default {
    namespaced: true,
    state: {
        currentUser: localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')) : null,
        loginError: null,
        processing: false,
        forgotMailSuccess: null,
        accountVerifySuccess: null,
        resetPasswordSuccess: null
    },
    getters: {
        belongsToUser: (state) => (userId) => {
            return state.currentUser && state.currentUser._id === userId;
        },
        currentUser: state => state.currentUser,
        processing: state => state.processing,
        loginError: state => state.loginError,
        forgotMailSuccess: state => state.forgotMailSuccess,
        accountVerifySuccess: state => state.accountVerifySuccess,
        resetPasswordSuccess: state => state.resetPasswordSuccess,
    },
    mutations: {
        setUser(state, payload) {
            state.currentUser = payload;
            state.processing = false;
            state.loginError = null
        },
        setLogout(state) {
            localStorage.removeItem('user');
            localStorage.removeItem('token');
            state.currentUser = {};
            state.processing = false;
            state.loginError = null;
        },
        setProcessing(state, payload) {
            state.processing = payload;
            state.loginError = null
        },
        setError(state, payload) {
            state.loginError = payload;
            state.currentUser = null;
            state.processing = false
        },
        setForgotMailSuccess(state) {
            state.loginError = null;
            state.currentUser = null;
            state.processing = false;
            state.forgotMailSuccess = true
        },
        setResetPasswordSuccess(state) {
            state.loginError = null;
            state.currentUser = null;
            state.processing = false;
            state.resetPasswordSuccess = true
        },
        setAccountVerifySuccess(state) {
            state.loginError = null;
            state.currentUser = null;
            state.processing = false;
            state.accountVerifySuccess = true
        },
        clearError(state) {
            state.loginError = null
        }
    },
    actions: {
        saveUser({state, commit}, payload) {
            localStorage.setItem('user', JSON.stringify(payload));
            commit('setUser', payload);
        },
        login({commit}, payload) {
            commit('clearError');
            commit('setProcessing', true);
            http.post('login', payload).then(res => {
                if (res.data.user) res.data.user.customer = res.data.customer;
                localStorage.setItem('user', JSON.stringify(res.data.user));
                localStorage.setItem('token', res.data.token);
                setTimeout(() => {
                    commit('setUser', res.data.user);
                }, 500);
            }).catch(error => {
                localStorage.removeItem('user');
                console.log('error', error);
                error = error.data || error;
                error = error.message || error;
                commit('setError', 'Error: ' + error);
                // setTimeout(() => {
                //     commit('clearError');
                // }, 3000)
            });
        },
        forgotPassword({commit}, payload) {
            commit('clearError');
            commit('setProcessing', true);
            return http.post('password/forgot', {
                server: location.protocol + '//' + location.host,
                email: payload.email
            }).then(
                user => {
                    commit('clearError');
                    commit('setForgotMailSuccess')
                },
                err => {
                    let error = err.data.hasOwnProperty('message') ? err.data.message : err.data;
                    commit('setError', error);
                    setTimeout(() => {
                        commit('clearError')
                    }, 3000)
                }
            );
        },
        resetPassword({commit}, payload) {
            commit('clearError');
            commit('setProcessing', true);
            return http.post('password/reset', payload).then(
                user => {
                    commit('clearError');
                    commit('setResetPasswordSuccess');
                    router.push('/user/login');
                }, err => {
                    let error = '';
                    if (err.data.hasOwnProperty('errors')) {
                        error = err.data.errors[Object.keys(err.data.errors)[0]][0];
                    } else if (err.data.hasOwnProperty('message')) {
                        error = err.data.hasOwnProperty('message') ? err.data.message : err.data;
                    }
                    commit('setError', error);
                    setTimeout(() => {
                        commit('clearError')
                    }, 3000)
                }
            );
        },
        verifyAccount({commit}, payload) {
            commit('clearError');
            commit('setProcessing', true);
            return http.put('verify-account', payload).then(
                user => {
                    commit('clearError');
                    commit('setAccountVerifySuccess');
                }, err => {
                    commit('setError', err.data);
                    setTimeout(() => {
                        commit('clearError')
                    }, 3000)
                }
            );
        },
        verifyToken({commit, dispatch}) {
            commit('setProcessing', true);
            return http.get('auth/verify-token').then(res => {
                if (!res.data || !res.data._id) dispatch('signOut');
            }).catch(err => {
                dispatch('signOut');
            }).finally(res => {
                commit('setProcessing', false);
            });
        },
        signOut({commit}) {
            return http.get('logout').then(() => {
                console.log('Logged out..');
            }).catch(error => {
                console.log('Log out error, but who cares?');
            }).finally(res => {
                commit('setLogout');
                router.push('/user/login');
            });
        },
    }
}
