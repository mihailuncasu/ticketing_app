import api from '@/api/users';

const users = {
    // State;
    state:() => ({
        users: []
    }),

    // Getters;
    getters: {
        users(state) {
            return state.users;
        }
    },

    // Mutations;
    mutations: {
        LOAD_USERS(state, users) {
            state.users = users;
        },
    },

    // Actions;
    actions: {
        loadUsers({commit}) {
            commit('START_LOADING');
            api.getUsers().then(result => {
                commit('LOAD_USERS', result.data.data);
                commit('STOP_LOADING');
            }).catch(error => {
               throw new Error(`API ${error}`);
            });
        }
    }
}

export default users;