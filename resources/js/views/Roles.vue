<template>
    <v-responsive width="1500">
        <v-data-table
                :headers="headers"
                :items="roles"
                sort-by="calories"
                class="elevation-1"
                :search="search"
        >
            <template v-slot:top>
                <v-toolbar flat color="balck">
                    <v-toolbar-title>ROLES</v-toolbar-title>
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
                            <v-btn color="primary" dark class="mb-2" v-on="on">New Role</v-btn>
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
                                                              label="Role name"
                                                              :rules="rules.name"
                                                              color="blue darken-2"
                                                              required
                                                ></v-text-field>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-select
                                                        v-model="editedItem.permissions"
                                                        :items="allPermissions.map(p => p.name)"
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
            <template v-slot:item.permissions="{ item }">
                <v-row justify="space-around">
                    <v-chip-group
                            multiple
                            :show-arrows="true"
                    >
                        <v-chip v-for="tag in item.permissions.map(x => allPermissions.find(p => p.id == x).name)"
                                :key="tag">
                            {{ tag }}
                        </v-chip>
                    </v-chip-group>
                </v-row>
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
            allPermissions: store.state.permissions,
            roles: store.state.roles,
            dialog: false,
            headers: [
                {
                    text: 'Role name',
                    align: 'start',
                    value: 'name',
                },
                {text: 'Role permissions', value: 'permissions', align: 'center'},
                {text: 'Actions', value: 'actions', sortable: false},
            ],
            editedIndex: -1,
            editedItem: {
                name: '',
                permissions: [],
            },
            defaultItem: {
                name: '',
                permissions: [],
            },
            rules: {
                name: [val => (val || '').length > 0 || 'This field is required'],
            },
            snackbar: false,
            search: '',
        }),

        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'New Role' : 'Edit Role'
            },
            formIsValid() {
                return (
                    this.editedItem.name &&
                    this.editedItem.permissions
                )
            },
            hintPermissions() {
                return 'Assign permissions to the new role';
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
                this.editedIndex = this.roles.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.editedItem.permissions = item.permissions.map(x => this.allPermissions.find(p => p.id == x).name);
                this.dialog = true
            },

            deleteItem(item) {
                const index = this.roles.indexOf(item);
                confirm('Are you sure you want to delete this role?') && this.roles.splice(index, 1)
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
                this.editedItem.permissions = this.editedItem.permissions.map(x => this.allPermissions.find(p => p.name == x).id);
                if (this.editedIndex > -1) {
                    // Edit
                    store.commit('editRole', this.editedItem);
                } else {
                    // Add
                    store.commit('addRole', this.editedItem);
                }
                this.snackbar = true;
                this.close()
            },
        },
    }
</script>