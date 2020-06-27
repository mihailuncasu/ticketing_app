<template>
    <v-data-table :headers="headers"
                  :items="roles"
                  sort-by="name"
                  class="elevation-1"
                  :loading="loading"
                  loading-text="Loading Roles... Please wait"
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
            <v-toolbar flat color="white">
                <v-toolbar-title>ROLES</v-toolbar-title>
                <v-divider class="mx-4"
                           inset
                           vertical
                ></v-divider>
                <v-spacer></v-spacer>
                <v-dialog v-model="dialog" max-width="500px">
                    <template v-slot:activator="{ on }">
                        <v-btn color="primary" dark class="mb-2" v-on="on">New Role</v-btn>
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
                                                          label="Role Name"
                                                          item-text="display_name"
                                                          :counter="lengths.role.max"
                                                          :rules="[...roleRules]"
                                                          :error-messages="errors.name"
                                                          @input="errors.name = []"
                                            />
                                        </v-col>
                                        <v-col cols="12">
                                            <v-select
                                                    v-model="input.permissions"
                                                    :items="permissions"
                                                    label="Permissions"
                                                    item-text="display_name"
                                                    return-object
                                                    multiple
                                                    chips
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
                        <v-card-title class="headline">Deleting Role</v-card-title>

                        <v-card-text>
                            Are you sure you want to delete this role?
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
        name: 'Roles',
        mixins: [rules],
        data: () => ({
            dialog: false,
            headers: [
                {
                    text: 'Role name',
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
                permissions: [],
            },
            defaultItem: {
                name: '',
                permissions: [],
            },
            errors: {},
            loading: false
        }),

        computed: {
            ...mapGetters({
                roles: 'roles/roles',
                permissions: 'permissions/permissions',
            }),

            formTitle() {
                return this.editedIndex === -1 ? 'New Role' : 'Edit Role'
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
                createRoleAction: 'roles/createAction',
                readRolesAction: 'roles/readAction',
                updateRoleAction: 'roles/updateAction',
                deleteRoleAction: 'roles/deleteAction',
                readPermissionsAction: 'permissions/readAction',
            }),

            updateTime() {
                this.now = new Date();
            },

            initialize() {
                this.loading = true;
                this.readPermissionsAction().then(() => {
                    this.readRolesAction().then(() => {
                        this.loading = false;
                    })
                })
            },

            askDeleteItem(item) {
                this.delete_dialog = true;
                this.deletedItem = item;
            },

            updateItem(item) {
                this.editedIndex = this.roles.indexOf(item)
                this.input = Object.assign({}, item)
                this.dialog = true
            },

            deleteItem() {
                this.loading = true;
                this.deleteRoleAction(this.deletedItem).then(() => {
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
                    this.updateRoleAction({
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
                    this.createRoleAction(this.input).then(() => {
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