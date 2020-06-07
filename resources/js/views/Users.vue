<template>
    <v-data-table
            :headers="headers"
            :items="users"
            sort-by="name"
            class="elevation-1"
            :loading="isLoading"
            loading-text="Loading Users... Please wait"
    >
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
                            <v-container>
                                <v-row>
                                    <v-col cols="12" sm="6" md="4">
                                        <v-text-field v-model="editedItem.name" label="Username"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="4">
                                        <v-text-field v-model="editedItem.email" label="Email"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="4">
                                        <v-text-field v-model="editedItem.role" label="Role"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="4">
                                        <v-text-field v-model="editedItem.created_at" label="Created"></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                            <v-btn color="blue darken-1" text @click="save">Save</v-btn>
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
</template>

<script>

    export default {
        data: () => ({
            dialog: false,
            headers: [
                {
                    text: 'Username',
                    align: 'start',
                    value: 'name',
                },
                {text: 'Email', value: 'email'},
                {text: 'Role', value: 'role'},
                {text: 'Created', value: 'created_at'},
                {text: 'Actions', value: 'actions', sortable: false},
            ],
            editedIndex: -1,
            editedItem: {
                name: '',
                email: '',
                role: {},
                permissions: [],
                created_at: ''
            },
            defaultItem: {
                name: '',
                email: '',
                role: {},
                permissions: [],
                created_at: ''
            },
        }),

        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'New User' : 'Edit User'
            },
            users() {
                return this.$store.getters.users;
            },
            isLoading() {
                return this.$store.getters.isLoading;
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

        methods: {
            initialize() {
                this.$store.dispatch('loadUsers');
            },

            editItem(item) {
                this.editedIndex = this.users.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },

            deleteItem(item) {
                const index = this.users.indexOf(item)
                confirm('Are you sure you want to delete this user?') && this.users.splice(index, 1)
            },

            close() {
                this.dialog = false
                this.$nextTick(() => {
                    this.editedUser = Object.assign({}, this.defaultUser)
                    this.editedIndex = -1
                })
            },

            save() {
                if (this.editedIndex > -1) {
                    Object.assign(this.users[this.editedIndex], this.editedItem)
                } else {
                    this.users.push(this.editedItem)
                }
                this.close()
            },
        },
    }
</script>