<template>
    <v-data-table
            :headers="headers"
            :items="users"
            sort-by="name"
            class="elevation-1"
            :loading="loading"
            loading-text="Loading Users... Please wait"
    >
        <template v-slot:item.name="{ item }">
            <span>{{ item.name | capitalize }}</span>
        </template>
        <template v-slot:item.role="{ item }">
            <span>{{ item.role.name | capitalize }}</span>
        </template>
        <template v-slot:item.created_at="{ item }">
            <span>{{ item.created_at | moment("calendar") }}</span>
        </template>
        <template v-slot:item.updated_at="{ item }">
            <span>{{ item.updated_at | moment("from", now) }}</span>
        </template>
        <template v-slot:top>
            <v-toolbar flat color="white">
                <v-toolbar-title>USERS</v-toolbar-title>
                <v-divider class="mx-4"
                           inset
                           vertical
                ></v-divider>
                <v-spacer></v-spacer>
                <v-dialog v-model="dialog" max-width="500px">
                    <template v-slot:activator="{ on }">
                        <v-btn color="primary" dark class="mb-2" v-on="on">New User</v-btn>
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
                                        <v-flex xs12>
                                            <v-text-field v-model="input.name"
                                                          label="Full Name"
                                                          name="name"
                                                          :counter="lengths.name.max"
                                                          :error-messages="errors.name"
                                                          :rules="[...nameRules]"
                                                          @input="errors.name = []"
                                            />
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-text-field v-model="input.email"
                                                          label="E-mail"
                                                          name="email"
                                                          :counter="lengths.email.max"
                                                          :error-messages="errors.email"
                                                          :rules="[...emailRules]"
                                                          @input="errors.email = []"
                                            />
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-select v-model="input.role"
                                                      :items="roles"
                                                      :rules="[...roleRules]"
                                                      label="Roles"
                                                      item-text="name"
                                                      return-object
                                                      required
                                                      chips
                                            />
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-select v-model="input.role.permissions"
                                                      :items="permissions"
                                                      label="Permissions"
                                                      item-text="name"
                                                      return-object
                                                      multiple
                                                      chips
                                            />
                                        </v-flex>
                                        <v-switch v-if="editedIndex < 0"
                                                  v-model="autoPassword"
                                                  label="Generate random password"
                                        />
                                        <v-flex xs12 v-if="!autoPassword">
                                            <v-text-field v-model="input.password"
                                                          label="Password"
                                                          :rules="[...passwordRules]"
                                                          :error-messages="errors.password"
                                                          @input="errors.password = []"
                                                          type="password"
                                            />
                                        </v-flex>
                                        <v-flex xs12 v-if="!autoPassword">
                                            <v-text-field v-model="input.password_confirmation"
                                                          label="Password Confirm"
                                                          :rules="[...passwordConfirmationRules]"
                                                          type="password"
                                            />
                                        </v-flex>
                                    </v-row>
                                </v-container>
                            </v-form>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                            <v-btn color="blue darken-1" text @click="save" :disabled="!valid">Save</v-btn>
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
                {text: 'Role', value: 'role'},
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
                role: {},
                permissions: [],
                password: '',
                password_confirmation: ''
            },
            defaultItem: {
                name: '',
                email: '',
                role: {},
                permissions: [],
                password: '',
                password_confirmation: ''
            },
            errors: {},
            loading: false
        }),
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                return value
                    .toLowerCase()
                    .split(' ')
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(' ');
            }
        },

        computed: {
            ...mapGetters({
                users: 'users/users',
                roles: 'roles/roles',
                permissions: 'permissions/permissions',
            }),

            formTitle() {
                return this.editedIndex === -1 ? 'New User' : 'Edit User'
            },
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
                    this.delete_dialog = false;
                    this.deletedItem = {};
                    this.loading = false;
                });
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
                    this.updateUserAction({
                        index: this.editedIndex,
                        item: this.input
                    }).then(() => {
                        this.now = new Date();
                        this.close();
                    }).catch(() => {
                        // Supposed errors;
                    });
                } else {
                    // Create;
                    this.createUserAction({...this.input, auto: this.autoPassword}).then(() => {
                        this.now = new Date();
                        this.close();
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