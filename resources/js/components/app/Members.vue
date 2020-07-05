<template>
    <v-data-table :headers="headers"
                  :items="members"
                  sort-by="name"
                  class="elevation-1"
                  :loading="loading"
                  loading-text="Loading Members... Please wait"
    >
        <template v-slot:item.name="{ item }">
            <span>{{ item.name }}</span>
        </template>
        <template v-slot:item.roles="{ item }">
            <v-chip-group show-arrows>
                <v-chip v-for="(role, index) in item.roles"
                        :key="index"
                        color="blue"
                        outlined
                >
                    {{ role.display_name }}
                </v-chip>
            </v-chip-group>
        </template>
        <template v-slot:item.created_at="{ item }">
            <span>{{ item.created_at | moment("calendar") }}</span>
        </template>
        <template v-slot:item.updated_at="{ item }">
            <span>{{ item.updated_at | moment("from", now) }}</span>
        </template>
        <template v-slot:top>
            <v-toolbar flat color="primary">
                <v-toolbar-title>Members</v-toolbar-title>
                <v-divider class="mx-4"
                           inset
                           vertical
                ></v-divider>
                <v-spacer></v-spacer>
                <v-dialog v-model="dialog" max-width="1000px">
                    <template v-slot:activator="{ on }">
                        <v-btn rounded @click="getPossibleMembers" class="mb-2" v-on="on">New Member
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="headline">{{ formTitle }}</span>
                        </v-card-title>

                        <v-card-text>
                            <v-form ref="form"
                                    v-model="valid"
                            >
                                <v-container>
                                    <v-row>
                                        <v-col cols="12" v-if="editedIndex < 0">
                                            <v-select v-model="input.user"
                                                      :disabled="loading"
                                                      :items="users"
                                                      :rules="[...newMemberRules]"
                                                      chips
                                                      hide-selected
                                                      color="blue white--text"
                                                      label="Select a new member"
                                                      return-object
                                                      no-data-text="No more users available"
                                            >
                                                <template v-slot:selection="data">
                                                    <v-chip v-bind="data.attrs"
                                                            :input-value="data.selected"
                                                            close
                                                            @click="data.select"
                                                            @click:close="remove(data.item)"
                                                    >
                                                        <v-avatar left>
                                                            <v-img :src="data.item.avatar"></v-img>
                                                        </v-avatar>
                                                        {{ data.item.name }}
                                                    </v-chip>
                                                </template>
                                                <template v-slot:item="data">
                                                    <template v-if="typeof data.item !== 'object'">
                                                        <v-list-item-content v-text="data.item"/>
                                                    </template>
                                                    <template v-else>
                                                        <v-list-item-avatar>
                                                            <img :src="data.item.avatar">
                                                        </v-list-item-avatar>
                                                        <v-list-item-content>
                                                            <v-list-item-title v-html="data.item.name"/>
                                                            <v-list-item-subtitle v-html="data.item.email"/>
                                                        </v-list-item-content>
                                                    </template>
                                                </template>
                                            </v-select>
                                        </v-col>
                                    </v-row>
                                    <v-row v-if="input.user">
                                        <v-col cols="12">
                                            <v-text-field v-model="input.user.name"
                                                          label="Full Name"
                                                          name="name"
                                                          filled
                                                          readonly
                                            />
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field v-model="input.user.email"
                                                          label="E-mail"
                                                          name="email"
                                                          filled
                                                          readonly
                                            />
                                        </v-col>
                                        <v-col cols="12">
                                            <v-select v-model="input.roles"
                                                      :items="roles"
                                                      label="Roles"
                                                      :rules="[...requiredRoleRules]"
                                                      item-text="display_name"
                                                      return-object
                                                      multiple
                                                      chips
                                                      outlined
                                            />
                                            <v-subheader v-if="input.roles.length">Permissions Granted By Roles
                                            </v-subheader>
                                            <v-expansion-panels popout
                                                                multiple
                                            >
                                                <v-expansion-panel v-for="(role, index) in input.roles"
                                                                   :key="index"
                                                                   v-if="role.permissions.length"
                                                >
                                                    <v-expansion-panel-header>
                                                        <b>{{role.display_name}}</b>Show Permissions
                                                    </v-expansion-panel-header>
                                                    <v-expansion-panel-content>
                                                        <v-chip-group column>
                                                            <v-chip v-for="(permission, index) in role.permissions"
                                                                    :key="index"
                                                                    color="blue"
                                                                    outlined
                                                            >
                                                                {{permission.display_name}}
                                                            </v-chip>
                                                        </v-chip-group>
                                                    </v-expansion-panel-content>
                                                </v-expansion-panel>
                                            </v-expansion-panels>
                                        </v-col>

                                        <v-col cols="12">
                                            <v-select v-model="input.permissions"
                                                      :items="remainingPermissions"
                                                      label="Extra Permissions"
                                                      item-text="display_name"
                                                      return-object
                                                      multiple
                                                      chips
                                                      outlined
                                                      no-data-text="This user has all permissions included in his roles"
                                            />
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-form>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                            <v-btn color="blue darken-1" text @click="save" :loading="loading" :disabled="!valid">Save
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <!--Delete-->
                <v-dialog v-model="delete_dialog"
                          max-width="290"
                >
                    <v-card>
                        <v-card-title class="headline">Remove Member</v-card-title>

                        <v-card-text>
                            Are you sure you want to delete this member from the group?
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>

                            <v-btn color="blue darken-1" text @click="delete_dialog = false">No</v-btn>
                            <v-btn color="red darken-1" text @click="deleteItem">Yes</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
            <v-icon small class="mr-2" @click="updateItem(item)">mdi-pencil</v-icon>
            <v-icon small @click="askDeleteItem(item)">mdi-delete</v-icon>
        </template>
        <template v-slot:no-data>
            <v-btn color="primary" @click="initialize">Reset</v-btn>
        </template>
    </v-data-table>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';
    import rules from '@/mixins/rules';
    import api from '@/api/members';

    export default {
        name: 'Members',
        mixins: [rules],
        data: () => ({
            dialog: false,
            headers: [
                {
                    text: 'Full name',
                    align: 'start',
                    value: 'name',
                },
                {text: 'Email', value: 'email'},
                {text: 'Roles', value: 'roles'},
                {text: 'Created', value: 'created_at'},
                {text: 'Updated', value: 'updated_at'},
                {text: 'Actions', value: 'actions', sortable: false},
            ],
            search: '',
            now: new Date(),
            valid: false,
            deletedItem: {},
            delete_dialog: false,
            editedIndex: -1,
            input: {
                user: null,
                roles: [],
                permissions: [],
            },
            defaultItem: {
                user: null,
                roles: [],
                permissions: [],
            },
            loading: false,
            users: [],
        }),

        computed: {
            ...mapGetters({
                members: 'members/members',
                roles: 'roles/roles',
                permissions: 'permissions/permissions',
            }),

            formTitle() {
                return this.editedIndex === -1 ? 'New Member' : 'Edit Member'
            },

            remainingPermissions() {
                if (this.dialog) {
                    let remainingPermissions = [];
                    let usedPermissions = [];

                    this.input.roles.forEach(r => {
                        usedPermissions = usedPermissions.concat(r.permissions);
                    });
                    this.permissions.forEach(p => {
                        if (usedPermissions.find(x => x.id === p.id) === undefined) {
                            remainingPermissions.push(p);
                        }
                    });
                    return remainingPermissions;
                }
            }
        },

        watch: {
            dialog(val) {
                val || this.close()
            },
        },

        created() {
            this.initialize()
        },

        mounted() {
            this.$options.interval = setInterval(this.updateTime, 1000);
        },

        methods: {
            ...mapActions({
                createMemberAction: 'members/createAction',
                readMembersAction: 'members/readAction',
                updateMemberAction: 'members/updateAction',
                deleteMemberAction: 'members/deleteAction',
                readRolesAction: 'roles/readAction',
                readPermissionsAction: 'permissions/readAction',
            }),

            getPossibleMembers() {
                this.loading = true;
                api.getPossibleMembers().then(({data}) => {
                    this.users = data.data;
                    this.loading = false;
                });
            },

            remove(item) {
                this.input.user = null;
            },

            updateTime() {
                this.now = new Date();
            },

            initialize() {
                this.loading = true;
                this.readPermissionsAction().then(() => {
                    this.readRolesAction().then(() => {
                        this.readMembersAction().then(() => {
                            this.loading = false;
                        });
                    })
                });
            },

            askDeleteItem(item) {
                this.delete_dialog = true;
                this.deletedItem = item;
            },

            updateItem(item) {
                this.editedIndex = this.members.indexOf(item)
                this.input.user = Object.assign({}, item);
                this.input.permissions = item.permissions;
                this.input.roles = item.roles;
                this.dialog = true
            },

            deleteItem(item) {
                this.loading = true;
                this.deleteMemberAction(this.deletedItem).then(() => {
                    this.loading = false;
                }).catch(() => {
                    this.loading = false;
                });
                this.deletedItem = {};
                this.delete_dialog = false;
            },

            close() {
                this.input = this.defaultItem;
                this.$refs.form.resetValidation();
                this.dialog = false
                this.loading = false;
                this.$nextTick(() => {
                    this.input = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                })
            },

            save() {
                this.loading = true;
                if (this.editedIndex > -1) {
                    // Update;
                    this.updateMemberAction({
                        index: this.editedIndex,
                        item: this.input
                    }).then(() => {
                        this.now = new Date();
                        this.close();
                        this.loading = false;
                    }).catch(() => {
                        this.loading = false;
                    });
                } else {
                    // Create;
                    this.createMemberAction({...this.input, auto: this.autoPassword}).then(() => {
                        this.now = new Date();
                        this.close();
                        this.loading = false;
                    }).catch(() => {
                        // Supposed errors;
                        this.loading = false;
                    });
                }
            },
        },
    }
</script>