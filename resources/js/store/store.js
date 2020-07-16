import Vue from 'vue'
import Vuex from 'vuex'

import users from "@/store/modules/users";
import roles from "@/store/modules/roles";
import permissions from "@/store/modules/permissions";
import auth from "@/store/modules/auth";
import application from "@/store/modules/application";
import groups from "@/store/modules/groups";
import members from "@/store/modules/members";
import messages from "@/store/modules/messages";
import tickets from "@/store/modules/tickets";

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {},
    mutations: {},
    actions: {},
    //strict: true,
    modules: {
        tickets,
        application,
        users,
        roles,
        permissions,
        auth,
        groups,
        members,
        messages,
    },
});

export default store