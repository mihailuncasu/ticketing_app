<template>
    <div>
        <v-data-table
                :headers="headers"
                :items="permissions"
                sort-by="name"
                class="elevation-1"
                :loading="loading"
                loading-text="Loading Permissions... Please wait"
        >
            <template v-slot:item.name="{ item }">
                <span>{{ item.display_name }}</span>
            </template>
            <template v-slot:item.created_at="{ item }">
                <span>{{ item.created_at | moment("calendar") }}</span>
            </template>
            <template v-slot:item.updated_at="{ item }">
                <span>{{ item.updated_at | moment("from", now) }}</span>
            </template>
            <template v-slot:top>
                <v-toolbar flat color="primary">
                    <v-toolbar-title>PERMISSIONS</v-toolbar-title>
                    <v-divider class="mx-4"
                               inset
                               vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <!--Edit-->
                    <v-dialog v-model="dialog" max-width="500px">
                        <template v-slot:activator="{ on }">
                            <v-btn rounded class="mb-2" v-on="on">New Permission</v-btn>
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
                                                              label="Permission Name"
                                                              :counter="lengths.permission.max"
                                                              :error-messages="errors.name"
                                                              :rules="[...permissionRules]"
                                                              @input="errors.name = []"
                                                />
                                            </v-col>
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
                            <v-card-title class="headline">Deleting Permission</v-card-title>

                            <v-card-text>
                                Are you sure you want to delete this permission?
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
                <v-icon small class="mr-2" @click="updateItem(item)">mdi-pencil
                </v-icon>
                <v-icon small @click="askDeleteItem(item)">mdi-delete
                </v-icon>
            </template>
            <template v-slot:no-data>
                <v-btn color="primary" @click="initialize">Reset</v-btn>
            </template>
        </v-data-table>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';
    import rules from '@/mixins/rules';

    export default {
        name: 'Permissions',
        mixins: [rules],
        data: () => ({
            dialog: false,
            headers: [
                {
                    text: 'Permission name',
                    align: 'start',
                    value: 'name',
                },
                {text: 'Created', value: 'created_at'},
                {text: 'Updated', value: 'updated_at'},
                {text: 'Actions', value: 'actions', sortable: false},
            ],
            now: new Date(),
            valid: false,
            deletedItem: {},
            delete_dialog: false,
            editedIndex: -1,
            input: {
                name: '',
            },
            defaultItem: {
                name: '',
            },
            errors: {},
            loading: false
        }),

        computed: {
            ...mapGetters({
                permissions: 'permissions/permissions',
            }),

            formTitle() {
                return this.editedIndex === -1 ? 'New Permission' : 'Edit Permission'
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
                createPermissionAction: 'permissions/createAction',
                readPermissionsAction: 'permissions/readAction',
                updatePermissionAction: 'permissions/updateAction',
                deletePermissionAction: 'permissions/deleteAction',
            }),

            updateTime() {
                this.now = new Date();
            },

            initialize() {
                this.loading = true;
                this.readPermissionsAction().then(() => {
                    this.loading = false;
                });
            },

            askDeleteItem(item) {
                this.delete_dialog = true;
                this.deletedItem = item;
            },

            updateItem(item) {
                this.editedIndex = this.permissions.indexOf(item)
                this.input = Object.assign({}, item)
                this.dialog = true
            },

            deleteItem() {
                this.loading = true;
                this.deletePermissionAction(this.deletedItem).then(() => {
                    this.loading = false;
                }).catch(() => {
                    this.loading = false;
                });
                this.delete_dialog = false;
                this.deletedItem = {};
            },

            close() {
                this.input = this.defaultItem;
                this.$refs.form.resetValidation();
                this.errors = {};
                this.dialog = false
                this.$nextTick(() => {
                    this.input = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                })
            },

            save() {
                this.loading = true;
                if (this.editedIndex > -1) {
                    // Update;
                    this.updatePermissionAction({
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
                    this.createPermissionAction(this.input).then(() => {
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