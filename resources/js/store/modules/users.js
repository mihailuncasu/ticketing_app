import api from '@/api/users';

const users = {
    // State;
    namespaced: true,
    state: () => ({
        users: [],
    }),

    // Getters;
    getters: {
        users(state) {
            return state.users;
        },
        user_lengths(state) {
            return state.user_lengths;
        }
    },

    // Mutations;
    mutations: {
        LOAD_USERS(state, payload) {
            state.users = payload;
        },
        DELETE_USER(state, payload) {
            const index = state.users.indexOf(payload);
            state.users.splice(index, 1);
        },
        SAVE_USER(state, payload) {
            state.users.push(payload);
        },
        EDIT_USER(state, payload) {
            Object.assign(state.users[payload.index], payload.item);
        }
    },

    // Actions;
    actions: {
        readAction({commit, dispatch}) {
            dispatch('application/showLoadingNotificationAction', {
                message: 'Getting users, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.getUsers().then(({data}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: 'Success',
                        color: 'green'
                    }, {root: true});
                    commit('LOAD_USERS', data.data);
                    resolve();
                }).catch(({response}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: response.data.message,
                        color: 'red'
                    }, {root: true});
                    reject();
                });
            });
        },

        createAction({commit, dispatch, state}, payload) {
            dispatch('application/showLoadingNotificationAction', {
                message: 'Creating user, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.saveUser(payload).then(({data}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: data.message,
                        color: 'green'
                    }, {root: true});
                    commit('SAVE_USER', data.payload);
                    resolve();
                }).catch(({response}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: response.data.message,
                        color: 'red'
                    }, {root: true});
                    reject(response.data);
                });
            })
        },

        deleteUser({commit, state}, payload) {
            commit('START_ACTION_LOADING');
            api.deleteUser(payload).then(result => {
                try {
                    // Remove the permission from the array;
                    commit('DELETE_USER', payload);
                    // Stop loading animation with success message;
                    commit('STOP_ACTION_LOADING_SUCCESS', {
                        message: result.data.message
                    });
                } catch (error) {
                    console.log(error)
                }
            }).catch(error => {
                // Stop loading animation with failure message;
                commit('STOP_ACTION_LOADING_FAILURE', {
                    message: error.response.data.message
                });
            });
        },
        editUser({commit, state}, payload) {
            commit('START_ACTION_LOADING');
            api.editUser(payload.item).then(result => {
                // Add permission;
                try {
                    commit('EDIT_USER', {
                        index: payload.index,
                        item: result.data.payload
                    });
                    // Stop loading animation with success message;
                    commit('STOP_ACTION_LOADING_SUCCESS', {
                        message: result.data.message
                    });
                } catch (error) {
                    console.log(error)
                }
            }).catch(error => {
                commit('STOP_ACTION_LOADING_FAILURE', {
                    message: error.response.data.message
                });
            });
        }
    }
}

export default users;