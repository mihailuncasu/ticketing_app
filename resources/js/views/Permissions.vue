<template>
    <div>
        <v-data-table
                :headers="headers"
                :items="permissions"
                sort-by="name"
                class="elevation-1"
                :loading="isLoading"
                loading-text="Loading Permissions... Please wait"
        >
            <template v-slot:item.created_at="{ item }">
                <span>{{ item.created_at | moment("calendar") }}</span>
            </template>
            <template v-slot:item.updated_at="{ item }">
                <span>{{ item.updated_at | moment("from", now) }}</span>
            </template>
            <template v-slot:top>
                <v-toolbar flat color="white">
                    <v-toolbar-title>PERMISSIONS</v-toolbar-title>
                    <v-divider
                            class="mx-4"
                            inset
                            vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <!--Edit-->
                    <v-dialog v-model="dialog" max-width="500px">
                        <template v-slot:activator="{ on }">
                            <v-btn color="primary" dark class="mb-2" v-on="on">New Permission</v-btn>
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
                                            <v-col cols="12">
                                                <v-text-field @keydown.enter.prevent
                                                              v-model.trim="editedItem.name"
                                                              label="Permission name"
                                                              :counter="lengths.max"
                                                              :rules="rules.permissions"
                                                ></v-text-field>
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
                    <v-dialog
                            v-model="delete_dialog"
                            max-width="290"
                    >
                        <v-card>
                            <v-card-title class="headline">Deleting Permission</v-card-title>

                            <v-card-text>
                                Are you sure you want to delete this permission?
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
    </div>
</template>

<script>

    export default {
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
            editedItem: {
                name: '',
            },
            defaultItem: {
                name: '',
            },
        }),

        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'New Permission' : 'Edit Permission'
            },
            permissions() {
                return this.$store.getters.permissions;
            },
            isLoading() {
                return this.$store.getters.isLoading;
            },
            lengths() {
                return this.$store.getters.permission_lengths;
            },
            rules() {
                const permissions = [
                    v => !!v || 'Permission name is required',
                    v => (v || '').length <= this.lengths.max || `Permission name must be less than ${this.lengths.max} characters`,
                    v => (v || '').length >= this.lengths.min || `Permission name must be more than ${this.lengths.min} characters`,
                    v => {
                        let item = this.permissions.find(p => p.name === v.trim());
                        if (item !== undefined) {
                            if (this.editedItem.id !== undefined) {
                                if (item.id !== this.editedItem.id) {
                                    return `Permission ${v.trim()} already exists`
                                } else {
                                    return true;
                                }
                            } else {
                                return `Permission ${v.trim()} already exists`
                            }
                        } else {
                            return true;
                        }
                    }
                ];
                return {
                    permissions: permissions
                };
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
            updateTime () {
                this.now = new Date();
            },

            initialize() {
                this.$store.dispatch('loadPermissions');
            },

            editItem(item) {
                this.editedIndex = this.permissions.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },

            askDeleteItem(item) {
                this.delete_dialog = true;
                this.deletedItem = item;
            },

            deleteItem() {
                this.$store.dispatch('deletePermission', this.deletedItem);
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
                    this.$store.dispatch('editPermission', {
                        index: this.editedIndex,
                        item: this.editedItem
                    });
                } else {
                    // Save;
                    this.$store.dispatch('savePermission', this.editedItem);
                }
                this.now = new Date();
                this.close();
            },
        },
    }
</script>