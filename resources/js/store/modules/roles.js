import api from '@/api/roles';

const roles = {
    // State;
    namespaced: true,
    state: () => ({
        roles: [],
    }),

    // Getters;
    getters: {
        roles(state) {
            return state.roles;
        },
    },

    // Mutations;
    mutations: {
        CREATE_ROLE(state, payload) {
            state.roles.push(payload);
        },

        STORE_ROLES(state, payload) {
            state.roles = payload;
        },

        UPDATE_ROLE(state, payload) {
            Object.assign(state.roles[payload.index], payload.item);
        },

        DELETE_ROLE(state, payload) {
            const index = state.roles.indexOf(payload);
            state.roles.splice(index, 1);
        },

    },

    // Actions;
    actions: {
        readAction({commit, dispatch}) {
            dispatch('application/showLoadingNotificationAction', {
                message: 'Getting roles, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.getRoles().then(({data}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: 'Success',
                        color: 'green'
                    }, {root: true});
                    commit('STORE_ROLES', data.data);
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
                message: 'Creating role, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.saveRole(payload).then(({data}) => {
                    commit('CREATE_ROLE', data.payload);
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
                message: 'Deleting role, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.deleteRole(payload).then(({data}) => {
                    commit('DELETE_ROLE', data.payload);
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
                message: 'Updating role, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.editRole(payload.item).then(({data}) => {
                    // Add permission;
                    commit('UPDATE_ROLE', {
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

export default roles;