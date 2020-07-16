import api from '@/api/tickets';

const tickets = {
    // State;
    namespaced: true,
    state: () => ({
        tickets: [],
    }),

    // Getters;
    getters: {
        active(state) {
            return state.tickets[1];
        },
        pending(state) {
            return state.tickets[0];
        },
        completed(state) {
            return state.tickets[2];
        },
    },

    // Mutations;
    mutations: {
        ADD_TICKET(state, payload) {
            state.tickets.push(payload);
        },

        STORE_TICKETS(state, payload) {
            state.tickets = payload;
        },

        UPDATE_TICKET(state, payload) {
            Object.assign(state.tickets[payload.index], payload.item);
        },

        REMOVE_TICKET(state, payload) {
            const index = state.tickets.indexOf(payload);
            state.tickets.splice(index, 1);
        },
    },

    // Actions;
    actions: {
        readAction({commit, dispatch}, verbose = true) {
            if (verbose) {
                dispatch('application/showLoadingNotificationAction', {
                    message: 'Getting tickets, please wait..',
                    color: 'black'
                }, {root: true});
            }
            return new Promise((resolve) => {
                api.getTickets().then(({data}) => {
                    if (verbose) {
                        dispatch('application/showResultNotificationAction', {
                            message: 'Success',
                            color: 'green'
                        }, {root: true});
                    }
                    commit('STORE_TICKETS', data);
                    resolve();
                }).catch(({response}) => {
                    if (verbose) {
                        dispatch('application/showResultNotificationAction', {
                            message: response.data.message,
                            color: 'red'
                        }, {root: true});
                    }
                });
            });
        },

        createAction({commit, dispatch}, payload) {
            dispatch('application/showLoadingNotificationAction', {
                message: 'Sending ticket, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.saveTicket(payload).then(({data}) => {
                    commit('ADD_TICKET', data.payload);
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

        updateStatusAction({commit, dispatch}, payload) {
            dispatch('application/showLoadingNotificationAction', {
                message: 'Updating ticket, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.updateStatus(payload).then(({data}) => {
                    commit('STORE_TICKETS', data.payload);
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
        },

        updateAction({commit, dispatch}, payload) {
            dispatch('application/showLoadingNotificationAction', {
                message: 'Updating ticket, please wait..',
                color: 'black'
            }, {root: true});
            return new Promise((resolve, reject) => {
                api.editTicket(payload.item).then(({data}) => {
                    // Add permission;
                    commit('UPDATE_TICKET', {
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

export default tickets;