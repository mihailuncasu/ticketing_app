<template>
    <v-data-table :headers="headers"
                  :items="users"
                  sort-by="name"
                  class="elevation-1"
                  :loading="loading"
                  loading-text="Loading Users... Please wait"
    >
        <template v-slot:item.name="{ item }">
            <span>{{ item.name }}</span>
        </template>
        <template v-slot:item.roles="{ item }">
            <v-chip-group show-arrows>
                <v-chip v-for="role in item.roles"
                        :key="role.id"
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
                <v-toolbar-title>USERS</v-toolbar-title>
                <v-divider class="mx-4"
                           inset
                           vertical
                ></v-divider>
                <v-spacer></v-spacer>
                <v-dialog v-model="dialog" max-width="1000px">
                    <template v-slot:activator="{ on }">
                        <v-btn rounded class="mb-2" v-on="on">New User</v-btn>
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
                                        <v-col cols="12">
                                            <v-text-field v-model="input.name"
                                                          label="Full Name"
                                                          name="name"
                                                          :counter="lengths.name.max"
                                                          :error-messages="errors.name"
                                                          :rules="[...nameRules]"
                                                          @input="errors.name = []"
                                            />
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field v-model="input.email"
                                                          label="E-mail"
                                                          name="email"
                                                          :counter="lengths.email.max"
                                                          :error-messages="errors.email"
                                                          :rules="[...emailRules]"
                                                          @input="errors.email = []"
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
                                                <v-expansion-panel v-for="role in input.roles"
                                                                   :key="role.id"
                                                                   v-if="role.permissions.length"
                                                >
                                                    <v-expansion-panel-header>
                                                        <b>{{role.display_name}}</b>Show Permissions
                                                    </v-expansion-panel-header>
                                                    <v-expansion-panel-content>
                                                        <v-chip-group column>
                                                            <v-chip v-for="permission in role.permissions"
                                                                    :key="permission.id"
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

                                        <v-switch v-if="editedIndex < 0"
                                                  v-model="autoPassword"
                                                  label="Generate random password"
                                        />
                                        <v-col cols="12" v-if="!autoPassword">
                                            <v-text-field v-model="input.password"
                                                          label="Password"
                                                          :rules="[...passwordRules]"
                                                          :error-messages="errors.password"
                                                          @input="errors.password = []"
                                                          type="password"
                                            />
                                        </v-col>
                                        <v-col cols="12" v-if="!autoPassword">
                                            <v-text-field v-model="input.password_confirmation"
                                                          label="Password Confirm"
                                                          :rules="[...passwordConfirmationRules]"
                                                          type="password"
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
                        <v-card-title class="headline">Deleting User</v-card-title>

                        <v-card-text>
                            Are you sure you want to delete this user?
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

    export default {
        name: 'Users',
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
            autoPassword: true,
            now: new Date(),
            valid: false,
            deletedItem: {},
            delete_dialog: false,
            editedIndex: -1,
            input: {
                name: '',
                email: '',
                roles: [],
                permissions: [],
                password: '',
                password_confirmation: ''
            },
            defaultItem: {
                name: '',
                email: '',
                roles: [],
                permissions: [],
                password: '',
                password_confirmation: ''
            },
            errors: {},
            loading: false,
        }),

        filters: {},

        computed: {
            ...mapGetters({
                users: 'users/users',
                roles: 'roles/roles',
                permissions: 'permissions/permissions',
            }),

            formTitle() {
                return this.editedIndex === -1 ? 'New User' : 'Edit User'
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
                createUserAction: 'users/createAction',
                readUsersAction: 'users/readAction',
                updateUserAction: 'users/updateAction',
                deleteUserAction: 'users/deleteAction',
                readRolesAction: 'roles/readAction',
                readPermissionsAction: 'permissions/readAction',
            }),

            updateTime() {
                this.now = new Date();
            },

            initialize() {
                this.loading = true;
                this.readPermissionsAction().then(() => {
                    this.readRolesAction().then(() => {
                        this.readUsersAction().then(() => {
                            this.loading = false;
                        });
                    })
                })
            },

            askDeleteItem(item) {
                this.delete_dialog = true;
                this.deletedItem = item;
            },

            updateItem(item) {
                this.editedIndex = this.users.indexOf(item)
                this.input = Object.assign({}, item)
                this.dialog = true
            },

            deleteItem(item) {
                this.loading = true;
                this.deleteUserAction(this.deletedItem).then(() => {
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
                this.errors = {};
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
                    this.updateUserAction({
                        index: this.editedIndex,
                        item: this.input
                    }).then(() => {
                        this.now = new Date();
                        this.close();
                        this.loading = false;
                    }).catch(({errors}) => {
                        this.errors = errors;
                        this.loading = false;
                    });
                } else {
                    // Create;
                    this.createUserAction({...this.input, auto: this.autoPassword}).then(() => {
                        this.now = new Date();
                        this.close();
                        this.loading = false;
                    }).catch(({errors}) => {
                        // Supposed errors;
                        this.errors = errors;
                        this.loading = false;
                    });
                }
            },
        },
    }
</script>