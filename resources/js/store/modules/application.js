const application = {
    namespaced: true,
    state: {
        notification: {
            message: '',
            color: '',
            visibility: false,
            loading: false,
            timeout: 4000
        },
    },
    // Shared getters;
    getters: {
        notification(state) {
            return state.notification;
        },
    },
    // Shared mutations;
    mutations: {
        SHOW_LOADING_NOTIFICATION(state, payload) {
            state.notification.loading = true;
            state.notification.message = payload.message;
            state.notification.color = payload.color;
            state.notification.visibility = true;
            state.notification.timeout = 0;
        },
        SHOW_RESULT_NOTIFICATION(state, payload) {
            state.notification.message = payload.message;
            state.notification.color = payload.color;
            state.notification.loading = false;
            state.notification.visibility = true;
            state.notification.timeout = 4000;
        },
        CLEAR_NOTIFICATION(state) {
            state.notification.message = '';
            state.notification.color = '';
            state.notification.visibility = false;
            state.notification.loading = false;
            state.notification.timeout = 4000;
        },
    },
    // Shared actions;
    actions: {
        clearNotificationAction({commit}) {
            commit('CLEAR_NOTIFICATION');
        },
        showLoadingNotificationAction({commit}, payload) {
            commit('SHOW_LOADING_NOTIFICATION', payload);
        },
        showResultNotificationAction({commit}, payload) {
            commit('SHOW_RESULT_NOTIFICATION', payload);
        }
    }
};

export default application