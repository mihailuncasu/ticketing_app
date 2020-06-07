import api from '@/api/roles';

const roles = {
    // State;
    state:() => ({
        roles: []
    }),

    // Getters;
    getters: {
        roles(state) {
            return state.roles;
        }
    },

    // Mutations;
    mutations: {
        LOAD_ROLES(state, payload) {
            state.roles = payload;
        },
    },

    // Actions;
    actions: {
        loadRoles({commit}) {
            commit('START_LOADING');
            api.getRoles().then(result => {
                commit('LOAD_ROLES', result.data.data);
                commit('STOP_LOADING');
            }).catch(error => {
               throw new Error(`API ${error}`);
            });
        }
    }
}

export default roles;