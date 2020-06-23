import api from '@/api/groups';

const groups = {
    // State;
    namespaced: true,
    state: () => ({
        groups: [],
    }),

    // Getters;
    getters: {
        groups(state) {
            return state.groups;
        },

    },

    // Mutations;
    mutations: {
        CREATE_GROUP(state, payload) {
            state.groups.push(payload);
        },

        STORE_GROUPS(state, payload) {
            state.groups = payload;
        },

        UPDATE_GROUP(state, payload) {
            Object.assign(state.group[payload.index], payload.item);
        },

        DELETE_GROUP(state, payload) {
            const index = state.groups.indexOf(payload);
            state.groups.splice(index, 1);
        }

    },

    // Actions;
    actions: {
        readAction({commit, dispatch}) {
            dispatch('application/showLoadingNotificationAction', {
                message: 'Getting groups, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve) => {
                api.getGroups().then(({data}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: 'Success',
                        color: 'green'
                    }, {root: true});
                    commit('STORE_GROUPS', data.data);
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
                message: 'Creating group, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.saveGroup(payload).then(({data}) => {
                    commit('CREATE_GROUP', data.payload);
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
                message: 'Deleting group, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.deleteGroup(payload).then(({data}) => {
                    try {
                        commit('DELETE_GROUP', payload);
                        dispatch('application/showResultNotificationAction', {
                            message: data.message,
                            color: 'green'
                        }, {root: true});
                        resolve();
                    } catch (e) {
                        console.log(e);
                    }
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
                message: 'Updating group, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.editGroup(payload.item).then(({data}) => {
                    commit('UPDATE_GROUP', {
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

export default groups;