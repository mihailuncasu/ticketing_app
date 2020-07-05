<template>
    <div>
        <v-data-table
                :headers="headers"
                :items="groups"
                sort-by="name"
                class="elevation-1"
                :loading="loading"
                loading-text="Loading Groups... Please wait"
        >
            <template v-slot:item.created_at="{ item }">
                <span>{{ item.created_at | moment("calendar") }}</span>
            </template>
            <template v-slot:item.updated_at="{ item }">
                <span>{{ item.updated_at | moment("from", now) }}</span>
            </template>
            <template v-slot:top>
                <v-toolbar flat color="primary">
                    <v-toolbar-title>GROUPS</v-toolbar-title>
                    <v-divider class="mx-4"
                               inset
                               vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <!--Edit-->
                    <v-dialog v-model="dialog" max-width="900px">
                        <template v-slot:activator="{ on }">
                            <v-btn rounded class="mb-2" v-on="on">New Group</v-btn>
                        </template>
                        <v-card :loading="loading">

                            <template v-slot:progress>
                                <v-progress-linear absolute
                                                   color="white"
                                                   height="4"
                                                   indeterminate
                                ></v-progress-linear>
                            </template>

                            <v-card-title class="headline font-weight-regular blue white--text">
                                <h3 v-if="input.name" class="headline">Group: {{input.name }}</h3>
                                <h3 v-else class="headline">New group</h3>
                            </v-card-title>
                            <v-card-text>
                                <v-form ref="form"
                                        v-model="valid">
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12">
                                                <v-text-field v-model="input.name"
                                                              :disabled="loading"
                                                              :rules="[...groupNameRules]"
                                                              label="Group name"
                                                              :counter="lengths.group_name.max"
                                                              :error-messages="errors.name"
                                                              @input="errors.name = []"
                                                />
                                            </v-col>

                                            <v-col cols="12">
                                                <v-textarea v-model="input.description"
                                                            :disabled="loading"
                                                            :rules="[...groupDescriptionRules]"
                                                            name="input-7-4"
                                                            label="Group description"
                                                            :auto-grow="false"
                                                            clearable
                                                            clear-icon="mdi-cancel"
                                                            :counter="lengths.group_description.max"
                                                            no-resize
                                                            rows="4"
                                                ></v-textarea>
                                            </v-col>
                                            <v-col cols="12">
                                                <v-autocomplete v-model="input.users"
                                                                :disabled="loading"
                                                                :items="users"
                                                                chips
                                                                color="blue white--text"
                                                                label="Select group admins"
                                                                multiple
                                                                item-value="id"
                                                                hide-no-data
                                                                hide-selected
                                                                :rules="[...groupManagersRules]"
                                                >
                                                    <template v-slot:selection="data">
                                                        <v-chip v-bind="data.attrs"
                                                                :input-value="data.selected"
                                                                close
                                                                @click="data.select"
                                                                @click:close="remove(data.item)"
                                                        >
                                                            <v-avatar left>
                                                                <v-img :src="data.item.avatar"></v-img>
                                                            </v-avatar>
                                                            {{ data.item.name }}
                                                        </v-chip>
                                                    </template>
                                                    <template v-slot:item="data">
                                                        <template v-if="typeof data.item !== 'object'">
                                                            <v-list-item-content v-text="data.item"/>
                                                        </template>
                                                        <template v-else>
                                                            <v-list-item-avatar>
                                                                <img :src="data.item.avatar">
                                                            </v-list-item-avatar>
                                                            <v-list-item-content>
                                                                <v-list-item-title v-html="data.item.name"/>
                                                                <v-list-item-subtitle v-html="data.item.email"/>
                                                            </v-list-item-content>
                                                        </template>
                                                    </template>
                                                </v-autocomplete>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-form>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                                <v-btn color="blue darken-1" text @click="save" :disabled="!valid" :loading="loading">
                                    Save
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <!--Delete-->
                    <v-dialog v-model="delete_dialog"
                              max-width="290"
                    >
                        <v-card>
                            <v-card-title class="headline">Deleting Group</v-card-title>

                            <v-card-text>
                                Are you sure you want to delete this group?
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
    </div>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';
    import rules from '@/mixins/rules';

    export default {
        name: 'Groups',
        mixins: [rules],
        data: () => ({
            dialog: false,
            headers: [
                {
                    text: 'Group name',
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
                description: '',
                users: [],
            },
            defaultItem: {
                name: '',
                description: '',
                users: [],
            },
            errors: {},
            loading: false,
        }),

        computed: {
            ...mapGetters({
                groups: 'groups/groups',
                users: 'users/users',
            }),

            formTitle() {
                return this.editedIndex === -1 ? 'New Group' : 'Edit Group'
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
            Echo.channel(`${window.subdomain}.group-event`)
                .listen('GroupCreatedEvent', (e) => {
                    console.log(e);
                });
        },

        methods: {
            ...mapActions({
                createGroupAction: 'groups/createAction',
                readGroupsAction: 'groups/readAction',
                updateGroupAction: 'groups/updateAction',
                deleteGroupAction: 'groups/deleteAction',
                readUsersAction: 'users/readAction',
            }),

            remove(item) {
                const index = this.input.users.indexOf(item.id);
                if (index >= 0) this.input.users.splice(index, 1)
            },

            updateTime() {
                this.now = new Date();
            },

            initialize() {
                this.loading = true;
                this.readUsersAction().then(() => {
                    this.readGroupsAction().then(() => {
                        this.loading = false;
                    })
                })
            },

            askDeleteItem(item) {
                this.delete_dialog = true;
                this.deletedItem = item;
            },

            updateItem(item) {
                this.editedIndex = this.groups.indexOf(item)
                this.input = Object.assign({}, item)
                this.input.users = this.input.users.map(u => u.id);
                this.dialog = true
            },

            deleteItem() {
                this.loading = true;
                this.deleteGroupAction(this.deletedItem).then(() => {
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
                    this.updateGroupAction({
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
                    this.createGroupAction(this.input).then(() => {
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