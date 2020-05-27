import Vue from 'vue'
import Vuex from 'vuex'

import roles from "@/store/modules/roles";
import pages from "@/store/modules/pages";
import users from "@/store/modules/users";
import permissions from "@/store/modules/permissions";

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        permissions: permissions,
        users: users,
        roles: roles,
        pages: pages,
        tasks: [
                {
                    done: false,
                    text: 'Task 1',
                },
                {
                    done: false,
                    text: 'Task 2',
                },
            ],
    },
    mutations: {
        addPermission(state, permission) {
            // Add a new permission
            let id = state.permissions[state.permissions.length - 1].id++;
            permission.id = id;
            state.permissions.push(permission);
        },
        editPermission(state, permission) {
            // Add a new permission
            Object.assign(state.permissions.find(x => x.id == permission.id), permission);
        },
        deletePermission(state, id) {
            // Delete a permission
            state.permissions = state.permissions.filter(p => p.id != id);
        },
        addRole(state, role) {
            // Add a new permission
            let id = state.roles[state.roles.length - 1].id++;
            role.id = id;
            state.roles.push(role);
        },
        editRole(state, role) {
            // Edit role;
            Object.assign(state.roles.find(x => x.id == role.id), role);
        },
        addTask(state, task) {
            state.tasks.push({
                    done: false,
                    text: task,
                });
        },
        completeTask(index) {

        },
        deleteRole(state, id) {
            // Add a new permission

        },
        addUser(state, role) {
            // Add a new permission

        },
        deleteUser(state, id) {
            // Add a new permission

        }
    }
});

export default store