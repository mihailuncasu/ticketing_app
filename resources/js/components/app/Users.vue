<template>
    <v-data-table
            :headers="headers"
            :items="users"
            sort-by="name"
            class="elevation-1"
            :loading="isLoading"
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
                <v-divider
                        class="mx-4"
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
                            <v-form
                                    ref="form"
                                    v-model="valid"
                            >
                                <v-container>
                                    <v-row>
                                        <v-flex xs12>
                                            <v-text-field v-model="editedItem.name" label="Full name"
                                                          @keydown.enter.prevent
                                                          :counter="lengths.name.max"
                                                          :rules="rules.name"
                                            ></v-text-field>
                                        </v-flex>


                                        <v-flex xs12>
                                            <v-text-field v-model="editedItem.email" label="Email"
                                                          @keydown.enter.prevent
                                                          :counter="lengths.email.max"
                                                          :rules="rules.email"
                                            ></v-text-field>
                                        </v-flex>

                                        <v-flex xs12>
                                            <v-select
                                                    v-model="editedItem.role"
                                                    :items="roles"
                                                    label="Roles"
                                                    item-text="name"
                                                    return-object
                                                    chips
                                            ></v-select>
                                        </v-flex>

                                        <v-flex xs12>
                                            <v-select
                                                    v-model="editedItem.role.permissions"
                                                    :items="permissions"
                                                    label="Permissions"
                                                    item-text="name"
                                                    return-object
                                                    multiple
                                                    chips
                                            ></v-select>
                                        </v-flex>

                                        <v-switch v-if="editedIndex < 0"
                                                  v-model="autoPassword"
                                                  label="Generate random password"
                                        ></v-switch>

                                        <v-flex xs12 v-if="!autoPassword">
                                            <v-text-field v-model="editedItem.password" label="Password"
                                                          :rules="rules.password"
                                            ></v-text-field>
                                        </v-flex>

                                        <v-flex xs12 v-if="!autoPassword">
                                            <v-text-field v-model="editedItem.confirm_password" label="Confirm Password"
                                                          :rules="rules.confirm_password"
                                            ></v-text-field>
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
                <v-dialog
                        v-model="delete_dialog"
                        max-width="290"
                >
                    <v-card>
                        <v-card-title class="headline">Deleting User</v-card-title>

                        <v-card-text>
                            Are you sure you want to delete this user?
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>

                            <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="delete_dialog = false"
                            >
                                No
                            </v-btn>

                            <v-btn
                                    color="red darken-1"
                                    text
                                    @click="deleteItem"
                            >
                                Yes
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
            <v-icon
                    small
                    class="mr-2"
                    @click="editItem(item)"
            >
                mdi-pencil
            </v-icon>
            <v-icon
                    small
                    @click="askDeleteItem(item)"
            >
                mdi-delete
            </v-icon>
        </template>
        <template v-slot:no-data>
            <v-btn color="primary" @click="initialize">Reset</v-btn>
        </template>
    </v-data-table>
</template>

<script>

    export default {
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
            editedItem: {
                name: '',
                email: '',
                role: {},
                permissions: [],
                password: '',
                confirm_password: ''
            },
            defaultItem: {
                name: '',
                email: '',
                role: {},
                permissions: [],
                password: '',
                confirm_password: ''
            },
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
            formTitle() {
                return this.editedIndex === -1 ? 'New User' : 'Edit User'
            },
            users() {
                return this.$store.getters.users;
            },
            isLoading() {
                return this.$store.getters.isLoading;
            },
            permissions() {
                return this.$store.getters.permissions;
            },
            lengths() {
                return this.$store.getters.user_lengths;
            },
            roles() {
                return this.$store.getters.roles;
            },
            rules() {
                const name = [
                    v => !!v || 'Full name is required',
                    v => (v || '').length <= this.lengths.name.max || `Full name must be less than ${this.lengths.name.max} characters`,
                    v => (v || '').length >= this.lengths.name.min || `Full name must be more than ${this.lengths.name.min} characters`,
                    v => (v.trim() || '').indexOf(' ') > 0 || 'Please provide user full name'
                ];
                const email = [
                    v => !!v || 'Email is required',
                    v => (v || '').length <= this.lengths.email.max || `Email must be less than ${this.lengths.email.max} characters`,
                    v => (v || '').length >= this.lengths.email.min || `Email must be more than ${this.lengths.email.min} characters`,
                    v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
                    v => {
                        let item = this.roles.find(p => p.email === v.trim());
                        if (item !== undefined) {
                            if (this.editedItem.id !== undefined) {
                                if (item.id !== this.editedItem.id) {
                                    return `Email ${v.trim()} already exists`
                                } else {
                                    return true;
                                }
                            } else {
                                return `Email ${v.trim()} already exists`
                            }
                        } else {
                            return true;
                        }
                    }
                ];
                const password = [
                    v => !!v || 'Password is required',
                    v => (v || '').length >= this.lengths.password.min || `Password must be more than ${this.lengths.password.min} characters`,
                    v => (v.trim() || '').indexOf(' ') < 0 || 'No white spaces are allowed'
                ];
                const confirm_password = [
                    v => !!v || 'Confirm password is required',
                    v => v === this.editedItem.password || `Password must match`
                ];
                return this.autoPassword ? {name: name, email: email} : {name: name, email: email, password: password, confirm_password: confirm_password}
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
            updateTime() {
                this.now = new Date();
            },

            initialize() {
                this.$store.dispatch('loadPermissions').then(p => {
                    this.$store.dispatch('loadRoles').then(r => {
                        this.$store.dispatch('loadUsers');
                    });
                });
            },

            askDeleteItem(item) {
                this.delete_dialog = true;
                this.deletedItem = item;
            },

            editItem(item) {
                this.editedIndex = this.users.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },

            deleteItem(item) {
                this.$store.dispatch('deleteUser', this.deletedItem);
                this.delete_dialog = false;
                this.deletedItem = {};
            },

            close() {
                this.$refs.form.resetValidation();
                this.dialog = false
                this.$nextTick(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                })
            },

            save() {
                if (this.editedIndex > -1) {
                    // Edit;
                    this.$store.dispatch('editUser', {
                        index: this.editedIndex,
                        item: this.editedItem
                    });
                } else {
                    // Save;
                    this.$store.dispatch('saveUser', this.editedItem);
                }
                this.now = new Date();
                this.close();
            },
        },
    }
</script>