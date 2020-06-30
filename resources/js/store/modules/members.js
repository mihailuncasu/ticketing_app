import api from '@/api/members';

const members = {
    // State;
    namespaced: true,
    state: () => ({
        members: [],
    }),

    // Getters;
    getters: {
        members(state) {
            return state.members;
        },
    },

    // Mutations;
    mutations: {
        ADD_MEMBER(state, payload) {
            state.members.push(payload);
        },

        STORE_MEMBERS(state, payload) {
            state.members = payload;
        },

        UPDATE_MEMBER(state, payload) {
            Object.assign(state.members[payload.index], payload.item);
        },

        REMOVE_MEMBER(state, payload) {
            const index = state.members.indexOf(payload);
            state.members.splice(index, 1);
        },
    },

    // Actions;
    actions: {
        readAction({commit, dispatch}) {
            dispatch('application/showLoadingNotificationAction', {
                message: 'Getting members, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve) => {
                api.getMembers().then(({data}) => {
                    dispatch('application/showResultNotificationAction', {
                        message: 'Success',
                        color: 'green'
                    }, {root: true});
                    commit('STORE_MEMBERS', data.data);
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
                message: 'Adding member, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.addMember(payload).then(({data}) => {
                    commit('ADD_MEMBER', data.payload);
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
                message: 'Removing member, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.removeMember(payload).then(({data}) => {
                    commit('REMOVE_MEMBER', payload);
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
                message: 'Updating member, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.editMember(payload.item).then(({data}) => {
                    // Add permission;
                    commit('UPDATE_MEMBER', {
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

export default members;