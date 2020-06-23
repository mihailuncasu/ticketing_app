import api from '@/api/permissions';

const permissions = {
    // State;
    namespaced: true,
    state: () => ({
        permissions: [],
    }),

    // Getters;
    getters: {
        permissions(state) {
            return state.permissions;
        },

    },

    // Mutations;
    mutations: {
        CREATE_PERMISSION(state, payload) {
            state.permissions.push(payload);
        },

        STORE_PERMISSIONS(state, payload) {
            state.permissions = payload;
        },

        UPDATE_PERMISSION(state, payload) {
            Object.assign(state.permissions[payload.index], payload.item);
        },

        DELETE_PERMISSION(state, payload) {
            const index = state.permissions.indexOf(payload);
            state.permissions.splice(index, 1);
        }

    },

    // Actions;
    actions: {
        readAction({commit, dispatch}) {
            dispatch('application/showLoadingNotificationAction', {
                message: 'Getting permissions, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve) => {
                api.getPermissions().then(({data}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: 'Success',
                        color: 'green'
                    }, {root: true});
                    commit('STORE_PERMISSIONS', data.data);
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
                message: 'Creating permisison, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.savePermission(payload).then(({data}) => {
                    commit('CREATE_PERMISSION', data.payload);
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
                message: 'Deleting permission, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.deletePermission(payload).then(({data}) => {
                    commit('DELETE_PERMISSION', payload);
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
                message: 'Updating permission, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.editPermission(payload.item).then(({data}) => {
                    // Add permission;
                    commit('UPDATE_PERMISSION', {
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

export default permissions;