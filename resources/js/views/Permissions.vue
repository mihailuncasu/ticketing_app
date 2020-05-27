<template>
    <v-responsive width="1200">
        <v-data-table
                :headers="headers"
                :items="permissions"
                sort-by="calories"
                class="elevation-1"
                :search="search"
        >
            <template v-slot:top>
                <v-toolbar flat color="balck">
                    <v-toolbar-title>PERMISSIONS</v-toolbar-title>
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
                            <v-btn color="primary" dark class="mb-2" v-on="on">New Permission</v-btn>
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
                                                              label="Permission name"
                                                              :rules="rules.name"
                                                              color="blue darken-2"
                                                              required
                                                ></v-text-field>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-select
                                                        v-model="editedItem.page"
                                                        :items="pages.map(x => x.page)"
                                                        label="Page"
                                                        item-text="name"
                                                        return-object
                                                        :hint="hintPermissions"
                                                        persistent-hint
                                                ></v-select>
                                            </v-flex>
                                            <v-flex>
                                                <v-expansion-panels>
                                            <v-expansion-panel>
                                                <v-expansion-panel-header disable-icon-rotate>
                                                    Page features
                                                    <template v-slot:actions>
                                                        <v-icon color="error">mdi-alert-circle</v-icon>
                                                    </template>
                                                </v-expansion-panel-header>
                                                <v-expansion-panel-content>
                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                                </v-expansion-panel-content>
                                            </v-expansion-panel>
                                                </v-expansion-panels>
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
            permissions: store.state.permissions,
            pages: store.state.pages,
            dialog: false,
            headers: [
                {
                    text: 'Page permission',
                    align: 'start',
                    value: 'name',
                },
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
                return this.editedIndex === -1 ? 'New Permission' : 'Edit Permission'
            },
            formIsValid() {
                return (
                    this.editedItem.name &&
                    this.editedItem.page
                )
            },
            hintPermissions() {
                return 'Granting permission means granting access to a page';
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
                this.editedIndex = this.permissions.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },

            deleteItem(item) {
                confirm('Are you sure you want to delete this permission?') && store.commit('deletePermission', item.id);
                this.permissions = store.state.permissions;
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
                    // Edit
                    store.commit('editPermission', this.editedItem);
                } else {
                    // Add
                    store.commit('addPermission', this.editedItem);
                }
                this.snackbar = true;
                this.close()
            },
        },
    }
</script>