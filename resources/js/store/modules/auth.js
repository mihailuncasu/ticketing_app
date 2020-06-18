import api from '@/api/auth';

const auth = {
    namespaced: true,
    // State;
    state: () => ({
        isLoggedIn: false,
        userDetails: {}
    }),

    // Getters;
    getters: {
        loggedIn(state) {
            return state.isLoggedIn;
        },
        userDetails(state) {
            return state.userDetails;
        }
    },

    // Mutations;
    mutations: {
        SET_LOGGED_IN(state, payload) {
            state.isLoggedIn = payload;
        },
        SET_USER_DETAILS(state, payload) {
            state.userDetails = payload;
        }
    },

    // Actions;
    actions: {
        registerAction({commit, state, dispatch}, payload) {
            // Start loading action;
            dispatch('application/showLoadingNotificationAction', {
                message: 'Registering, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.register(payload).then(({data}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: data.message,
                        color: 'green'
                    }, {root: true});
                    resolve(data);
                }).catch(({response}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: response.data.message,
                        color: 'red'
                    }, {root: true});
                    reject(response.data);
                });
            });
        },

        resetPasswordAction({commit, dispatch}, payload) {
            dispatch('application/showLoadingNotificationAction', {
                message: 'Sending email, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.resetPassword(payload).then(({data}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: data.message,
                        color: 'green'
                    }, {root: true});
                    resolve({redirect: data.redirect});
                }).catch(({response}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: response.data.message,
                        color: 'red'
                    }, {root: true});
                    reject();
                });
            });
        },

        forgotPasswordAction({commit, dispatch}, payload) {
            dispatch('application/showLoadingNotificationAction', {
                message: 'Sending email, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve) => {
                api.forgotPassword(payload).then(({data}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: data.message,
                        color: 'green'
                    }, {root: true});
                }).catch(({response}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: response.data.message,
                        color: 'red'
                    }, {root: true});
                });
                resolve();
            })
        },

        domainLoginAction: function ({commit, state, dispatch}, payload) {
            // Start loading action;
            dispatch('application/showLoadingNotificationAction', {
                message: 'Checking domain, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.domainLogin(payload).then(({data}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: data.message,
                        color: 'green'
                    }, {root: true});
                    resolve({redirect: data.redirect});
                }).catch(({response}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: response.data.message,
                        color: 'red'
                    }, {root: true});
                    if (response.data.errors !== undefined) {
                        reject(response.data);
                    } else {
                        reject({errors: false});
                    }
                });
            });
        },

        loginAction: function ({commit, state, dispatch}, payload) {
            // Start loading action;
            dispatch('application/showLoadingNotificationAction', {
                message: 'Authenticating, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.login(payload).then(({data}) => {
                    localStorage.setItem('token', data.access_token);
                    commit('SET_LOGGED_IN', true);
                    dispatch('profileAction').then(() => {
                        dispatch('application/showResultNotificationAction', {
                            message: data.message,
                            color: 'green'
                        }, {root: true});
                        resolve({redirect: data.redirect});
                    }).catch(({response}) => {
                        reject(response);
                    });
                }).catch(({response}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: response.data.message,
                        color: 'red'
                    }, {root: true});
                    if (response.data.errors !== undefined) {
                        reject(response.data);
                    } else {
                        reject({errors: false});
                    }
                });
            });
        },

        logoutAction({commit, dispatch}) {
            dispatch('application/showLoadingNotificationAction', {
                message: 'Logging out, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve) => {
                api.logout().then(({data}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: data.message,
                        color: 'green'
                    }, {root: true});
                    localStorage.removeItem('token');
                    commit('SET_LOGGED_IN', false);
                    resolve();
                }).catch(({response}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: response.data.message,
                        color: 'red'
                    }, {root: true});
                    localStorage.removeItem('token');
                    commit('SET_LOGGED_IN', false);
                    resolve();
                });
            });
        },

        profileAction({commit}) {
            return new Promise((resolve, reject) => {
                api.profile().then(({data}) => {
                    commit('SET_USER_DETAILS', data.data);
                    resolve(data);
                }).catch(({response}) => {
                    reject(response);
                });
            });
        }
    }
}

export default auth;