import api from '@/api/messages';

const messages = {
    // State;
    namespaced: true,
    state: () => ({
        messages: [],
    }),

    // Getters;
    getters: {
        messages(state) {
            return state.messages;
        },

    },

    // Mutations;
    mutations: {
        CREATE_MESSAGE(state, payload) {
            state.messages.push(payload);
        },

        STORE_MESSAGES(state, payload) {
            state.messages = payload;
        },

    },

    // Actions;
    actions: {
        readAction({commit}) {
            return new Promise((resolve, reject) => {
                api.getMessages().then(({data}) => {
                    commit('STORE_MESSAGES', data.data);
                    resolve();
                }).catch(({response}) => {
                    reject(response.data);
                });
            });
        },

        createAction({commit, dispatch}, payload) {
            return new Promise((resolve, reject) => {
                api.saveMessage(payload).then(({data}) => {
                    //commit('CREATE_MESSAGE', data.payload);
                    resolve();
                }).catch(({response}) => {
                    reject(response.data);
                });
            })
        },
    }
}

export default messages;