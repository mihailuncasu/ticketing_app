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
    },

    // Mutations;
    mutations: {
        CREATE_USER(state, payload) {
            state.users.push(payload);
        },

        STORE_USERS(state, payload) {
            state.users = payload;
        },

        UPDATE_USER(state, payload) {
            Object.assign(state.users[payload.index], payload.item);
        },

        DELETE_USER(state, payload) {
            const index = state.users.indexOf(payload);
            state.users.splice(index, 1);
        },
    },

    // Actions;
    actions: {
        readAction({commit, dispatch}) {
            dispatch('application/showLoadingNotificationAction', {
                message: 'Getting users, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve) => {
                api.getUsers().then(({data}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: 'Success',
                        color: 'green'
                    }, {root: true});
                    commit('STORE_USERS', data.data);
                    resolve();
                }).catch(({response}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: response.data.message,
                        color: 'red'
                    }, {root: true});
                });
            });
        },

        createAction({commit, dispatch}, payload) {
            dispatch('application/showLoadingNotificationAction', {
                message: 'Creating user, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.saveUser(payload).then(({data}) => {
                    commit('CREATE_USER', data.payload);
                    dispatch('application/showResultNotificationAction', {
                        message: data.message,
                        color: 'green'
                    }, {root: true});
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

        deleteAction({commit, dispatch}, payload) {
            dispatch('application/showLoadingNotificationAction', {
                message: 'Deleting user, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.deleteUser(payload).then(({data}) => {
                    commit('DELETE_USER', payload);
                    dispatch('application/showResultNotificationAction', {
                        message: data.message,
                        color: 'green'
                    }, {root: true});
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

        updateAction({commit, dispatch}, payload) {
            dispatch('application/showLoadingNotificationAction', {
                message: 'Updating user, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.editUser(payload.item).then(({data}) => {
                    // Add permission;
                    commit('UPDATE_USER', {
                        index: payload.index,
                        item: data.payload
                    });
                    dispatch('application/showResultNotificationAction', {
                        message: data.message,
                        color: 'green'
                    }, {root: true});
                    resolve();
                }).catch(({response}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: response.data.message,
                        color: 'red'
                    }, {root: true});
                    reject(response.data);
                });
            });
        }
    }
}

export default users;