import Vue from 'vue'
import Vuex from 'vuex'

import users from "@/store/modules/users";
import roles from "@/store/modules/roles";
import permissions from "@/store/modules/permissions";
//import pages from "@/store/modules/pages";

Vue.use(Vuex)

const store = new Vuex.Store({
    //strict: true,
    modules: {
        users,
        roles,
        permissions,
        //pages,
    },
    state: {
        isLoading: false,
        responseSnackbar: {
            message: '',
            color: '',
            visibility: false,
            close: true,
        },
    },
    // Shared getters;
    getters: {
        isLoading(state) {
            return state.isLoading;
        },
        responseSnackbar(state) {
            return state.responseSnackbar;
        }
    },
    // Shared mutations;
    mutations: {
        START_DATA_LOADING(state) {
            state.isLoading = true;
        },
        STOP_DATA_LOADING_SUCCESS(state) {
            state.isLoading = false;
        },
        STOP_DATA_LOADING_FAILURE(state, payload) {
            state.isLoading = false;
            state.responseSnackbar.message = payload.message;
            state.responseSnackbar.color = 'error';
            state.responseSnackbar.visibility = true;
        },
        START_ACTION_LOADING(state) {
            state.responseSnackbar.message = 'Processing request..';
            state.responseSnackbar.color = 'info';
            state.responseSnackbar.visibility = true;
            state.responseSnackbar.close = false;
        },
        STOP_ACTION_LOADING(state) {

        },
        CLOSE_ACTION(state) {
            state.responseSnackbar.visibility = false;
        },
        STOP_ACTION_LOADING_SUCCESS(state, payload) {
            state.responseSnackbar.message = payload.message;
            state.responseSnackbar.color = 'success';
            state.responseSnackbar.visibility = true;
            state.responseSnackbar.close = true;
        },
        STOP_ACTION_LOADING_FAILURE(state, payload) {
            state.responseSnackbar.message = payload.message;
            state.responseSnackbar.color = 'error';
            state.responseSnackbar.visibility = true;
            state.responseSnackbar.close = true;
        },
    },
    // Shared actions;
    actions: {
        closeAction({commit}) {
            commit('CLOSE_ACTION');
        }
    }
});

export default store