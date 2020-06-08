import api from '@/api/users';

const users = {
    // State;
    state:() => ({
        users: [],
        user_lengths: {
            name: {
                min: 5,
                max: 15
            },
            email: {
                min: 5,
                max: 30
            }
        }
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
        loadUsers({commit}) {
            commit('START_DATA_LOADING');
            api.getUsers().then(result => {
                commit('LOAD_USERS', result.data.data);
                commit('STOP_DATA_LOADING_SUCCESS');
            }).catch(error => {
               commit('STOP_DATA_LOADING_FAILURE', {
                    message: error.message
                });
            });
        },
        saveUser({commit, state}, payload) {
            commit('START_ACTION_LOADING');
            api.saveUser(payload).then(result => {
                // Add permission;
                try {
                    commit('SAVE_USER', result.data.payload);
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