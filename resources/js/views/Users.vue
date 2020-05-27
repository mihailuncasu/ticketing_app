<template>
    <v-responsive width="1500">
        <v-data-table
                :headers="headers"
                :items="users"
                sort-by="calories"
                class="elevation-1"
                :search="search"
        >
            <template v-slot:top>
                <v-toolbar flat color="balck">
                    <v-toolbar-title>USERS</v-toolbar-title>
                    <v-divider
                            class="mx-4"
                            inset
                            vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <v-text-field
                            v-model="search"
                            append-icon="mdi-magnify"
                            label="Search"
                            single-line
                            hide-details
                    ></v-text-field>
                    <v-divider
                            class="mx-4"
                            inset
                            vertical
                    ></v-divider>
                    <v-dialog v-model="dialog" max-width="500px">
                        <template v-slot:activator="{ on }">
                            <v-btn color="primary" dark class="mb-2" v-on="on">New User</v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{ formTitle }}</span>
                            </v-card-title>

                            <v-card-text>
                                <v-snackbar
                                        v-model="snackbar"
                                        absolute
                                        top
                                        right
                                        color="success"
                                ><span>Registration successful!</span>
                                    <v-icon dark>mdi-checkbox-marked-circle</v-icon>
                                </v-snackbar>
                                <v-form ref="form" @submit.prevent="submit">
                                    <v-container grid-list-md>
                                        <v-layout wrap>
                                            <v-flex xs12>
                                                <v-text-field v-model="editedItem.name"
                                                              label="Employee name"
                                                              :rules="rules.name"
                                                              color="blue darken-2"
                                                              required
                                                ></v-text-field>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-text-field v-model="editedItem.email"
                                                              label="Email"
                                                              :rules="rules.email"
                                                              color="blue darken-2"
                                                              required
                                                ></v-text-field>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-select
                                                        v-model="editedItem.role"
                                                        :items="allRoles"
                                                        label="Role"
                                                        item-text="name"
                                                        return-object
                                                        chips
                                                ></v-select>
                                            </v-flex>

                                            <v-flex xs12>
                                                <v-select
                                                        v-model="editedItem.permissions"
                                                        :items="allPermissions"
                                                        label="Permissions"
                                                        item-text="name"
                                                        return-object
                                                        multiple
                                                        chips
                                                        :hint="hintPermissions"
                                                        persistent-hint
                                                ></v-select>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-form>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                                <v-btn color="blue darken-1"
                                       text
                                       @click="save"
                                       :disabled="!formIsValid"
                                >Save
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
                        @click="deleteItem(item)"
                >
                    mdi-delete
                </v-icon>
            </template>
            <template v-slot:no-data>
                <v-btn color="primary" @click="initialize">Reset</v-btn>
            </template>
        </v-data-table>
    </v-responsive>
</template>

<script>
    import store from "@/store/index";

    export default {
        data: () => ({
            allPermissions: store.state.permissions.map(p => p.name),
            allRoles: store.state.roles.map(r => r.name),
            users: store.state.users,
            dialog: false,
            headers: [
                {
                    text: 'Name',
                    align: 'start',
                    value: 'name',
                },
                {text: 'Email', value: 'email'},
                {text: 'Role', value: 'role'},
                {text: 'Custom permissions', value: 'permissions'},
                {text: 'Created', value: 'created_at'},
                {text: 'Actions', value: 'actions', sortable: false},
            ],
            editedIndex: -1,
            editedItem: {
                name: '',
                email: '',
                role: '',
                permissions: [],
                created_at: '2020-04-15',
            },
            defaultItem: {
                name: '',
                email: '',
                role: '',
                permissions: [],
                created_at: '2020-04-15',
            },
            rules: {
                name: [val => (val || '').length > 0 || 'This field is required'],
                email: [val => (val || '').length > 0 || 'This field is required'],
            },
            snackbar: false,
            search: '',
        }),

        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'New User' : 'Edit User'
            },
            formIsValid() {
                return (
                    this.editedItem.name &&
                    this.editedItem.email
                )
            },
            hintPermissions() {
                return this.editedItem.role == '' ? '' : 'This user already has ' + this.editedItem.role + ' permissions';
            }
        },

        watch: {
            dialog(val) {
                val || this.close()
            },
        },

        mounted() {

        },

        methods: {
            editItem(item) {
                this.editedIndex = this.users.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },

            deleteItem(item) {
                console.log(item);
                const index = this.users.indexOf(item)
                confirm('Are you sure you want to delete this user?') && this.users.splice(index, 1)
            },

            close() {
                setTimeout(() => {
                    this.snackbar = false;
                }, 300)
                setTimeout(() => {
                    this.dialog = false;
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                }, 300)
            },

            save() {
                if (this.editedIndex > -1) {
                    Object.assign(this.users[this.editedIndex], this.editedItem);
                } else {
                    this.users.push(this.editedItem);
                }
                this.snackbar = true;
                this.close()
            },
        },
    }
</script>