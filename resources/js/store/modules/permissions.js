import api from '@/api/permissions';

const permissions = {
    // State;
    state: () => ({
        permissions: [],
        permission_lengths: {
            max: 15,
            min: 3
        },
    }),

    // Getters;
    getters: {
        permissions(state) {
            return state.permissions;
        },
        permission_lengths(state) {
            return state.permission_lengths;
        }
    },

    // Mutations;
    mutations: {
        LOAD_PERMISSIONS(state, payload) {
            state.permissions = payload;
        },
        DELETE_PERMISSION(state, payload) {
            const index = state.permissions.indexOf(payload);
            state.permissions.splice(index, 1);
        },
        SAVE_PERMISSION(state, payload) {
            state.permissions.push(payload);
        },
        EDIT_PERMISSION(state, payload) {
            Object.assign(state.permissions[payload.index], payload.item);
        }
    },

    // Actions;
    actions: {
        loadPermissions({commit}) {
            commit('START_DATA_LOADING');
            api.getPermissions().then(result => {
                commit('LOAD_PERMISSIONS', result.data.data);
                commit('STOP_DATA_LOADING_SUCCESS');
            }).catch(error => {
                commit('STOP_DATA_LOADING_FAILURE', {
                    message: error.message
                });
            });
        },
        savePermission({commit, state}, payload) {
            commit('START_ACTION_LOADING');
            api.savePermission(payload).then(result => {
                // Add permission;
                try {
                    commit('SAVE_PERMISSION', result.data.payload);
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
        deletePermission({commit, state}, payload) {
            commit('START_ACTION_LOADING');
            api.deletePermission(payload).then(result => {
                try {
                    // Remove the permission from the array;
                    commit('DELETE_PERMISSION', payload);
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
        editPermission({commit, state}, payload) {
            commit('START_ACTION_LOADING');
            api.editPermission(payload.item).then(result => {
                // Add permission;
                try {
                    commit('EDIT_PERMISSION', {
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

export default permissions;