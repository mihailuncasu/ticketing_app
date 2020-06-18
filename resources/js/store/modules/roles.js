import api from '@/api/roles';

const roles = {
    // State;
    namespaced: true,
    state:() => ({
        roles: [],
    }),

    // Getters;
    getters: {
        roles(state) {
            return state.roles;
        },
        role_lengths(state) {
            return state.role_lengths;
        }
    },

    // Mutations;
    mutations: {
        LOAD_ROLES(state, payload) {
            state.roles = payload;
        },
        DELETE_ROLE(state, payload) {
            const index = state.roles.indexOf(payload);
            state.roles.splice(index, 1);
        },
        SAVE_ROLE(state, payload) {
            state.roles.push(payload);
        },
        EDIT_ROLE(state, payload) {
            Object.assign(state.roles[payload.index], payload.item);
        }
    },

    // Actions;
    actions: {
        readAction({commit, dispatch}) {
            dispatch('application/showLoadingNotificationAction', {
                message: 'Getting data, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.getRoles().then(result => {
                    dispatch('application/showResultNotificationAction', {
                        message: 'Success',
                        color: 'green'
                    }, {root: true});
                    commit('LOAD_ROLES', result.data.data);
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

        saveRole({commit, state}, payload) {
            commit('START_ACTION_LOADING');
            api.saveRole(payload).then(result => {
                try {
                    commit('SAVE_ROLE', result.data.payload);
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
        },
        deleteRole({commit, state}, payload) {
            commit('START_ACTION_LOADING');
            api.deleteRole(payload).then(result => {
                try {
                    commit('DELETE_ROLE', payload);
                    // Stop loading animation with success message;
                    commit('STOP_ACTION_LOADING_SUCCESS', {
                        message: result.data.message
                    });
                } catch (error) {
                    console.log(error);
                }
            }).catch(error => {
                // Stop loading animation with failure message;
                commit('STOP_ACTION_LOADING_FAILURE', {
                    message: error.response.data.message
                });
            });
        },
        editRole({commit, state}, payload) {
            commit('START_ACTION_LOADING');
            api.editRole(payload.item).then(result => {
                try {
                    commit('EDIT_ROLE', {
                        index: payload.index,
                        item: result.data.payload
                    });
                    // Stop loading animation with success message;
                    commit('STOP_ACTION_LOADING_SUCCESS', {
                        message: result.data.message
                    });
                } catch (error) {
                    console.log(error);
                }
            }).catch(error => {
                commit('STOP_ACTION_LOADING_FAILURE', {
                    message: error.response.data.message
                });
            });
        }
    }
}

export default roles;