<template>
    <v-stepper v-model="stepNo"
               vertical
    >
        <v-form v-model="valid">
            <v-toolbar flat color="primary">
                <v-toolbar-title>CREATE TICKET</v-toolbar-title>
            </v-toolbar>
            <v-divider class="mx-4"
                       inset
                       vertical
            ></v-divider>
            <v-spacer></v-spacer>
            <v-stepper-step :rules="[() => input.title.length >= lengths.ticket_title.min]"
                            :complete="stepNo > 1"
                            step="1"
                            editable
            >
                Title
                <small v-if="stepNo == 1">
                    Enter ticket title
                </small>
                <small v-else>
                    {{input.title}}
                </small>
            </v-stepper-step>
            <v-stepper-content step="1" class="pa-3">
                <v-col class="pa-2">
                    <v-text-field style="width: 70vh"
                                  v-model="input.title"
                                  :counter="lengths.ticket_title.max"
                                  :rules="[...ticketRules.title]"
                    />
                    <v-btn color="primary" @click="stepNo++">Continue</v-btn>
                    <v-btn text @click="stepNo--">Back</v-btn>
                </v-col>
            </v-stepper-content>

            <v-stepper-step :rules="[() => input.priority.display !== undefined]"
                            :complete="stepNo > 2"
                            step="2"
                            editable
            >
                Problem priority
                <small v-if="stepNo != 2">
                    {{input.priority.display}}
                </small>
            </v-stepper-step>
            <v-stepper-content step="2" class="pa-5">
                <v-col class="pa-2">
                    <v-select style="width: 70vh"
                              v-model="input.priority"
                              :items="items"
                              label="Select a priority"
                              dense
                              item-value="value"
                              item-text="display"
                              return-object
                              outlined
                    ></v-select>
                    <v-btn color="primary" @click="stepNo++">Continue</v-btn>
                    <v-btn text @click="stepNo--">Back</v-btn>
                </v-col>
            </v-stepper-content>

            <v-stepper-step :rules="[() => input.author !== null || input.selfMade]"
                            :complete="stepNo > 3"
                            step="3"
                            editable
                            @click="getUsers"
            >
                Who has this problem?
            </v-stepper-step>
            <v-stepper-content step="3" class="pa-5">
                <v-col class="pa-2">
                    <v-checkbox v-model="input.selfMade" label="I am the author of the ticket"/>
                    <v-autocomplete v-if="!input.selfMade"
                                    style="width: 50vh"
                                    v-model="input.author"
                                    :items="users"
                                    chips
                                    color="blue white--text"
                                    label="Select ticket author"
                                    item-value="id"
                                    hide-no-data
                                    hide-selected
                                    :loading="loading"
                    >
                        <template v-slot:selection="data">
                            <v-chip v-bind="data.attrs"
                                    :input-value="data.selected"
                                    close
                                    @click="data.select"
                                    @click:close="removeAuthor(data.item)"
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
                    <v-btn color="primary" @click="stepNo++">Continue</v-btn>
                    <v-btn text @click="stepNo--">Back</v-btn>
                </v-col>
            </v-stepper-content>

            <v-stepper-step :rules="[() => input.users!==null || input.autoAssign]"
                            step="4"
                            editable
                            :complete="stepNo > 4"
                            @click="getUsers"
            >
                Assign the ticket to a specialist
            </v-stepper-step>
            <v-stepper-content step="4" class="pa-5">
                <v-col class="pa-2">
                    <v-checkbox v-model="input.autoAssign" label="Auto assign the ticket"/>
                    <v-autocomplete v-if="!input.autoAssign"
                                    style="width: 50vh"
                                    v-model="input.users"
                                    :items="users"
                                    chips
                                    color="blue white--text"
                                    label="Select a technician"
                                    item-value="id"
                                    hide-no-data
                                    hide-selected
                    >
                        <template v-slot:selection="data">
                            <v-chip v-bind="data.attrs"
                                    :input-value="data.selected"
                                    close
                                    @click="data.select"
                                    @click:close="removeSpecialist(data.item)"
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
                    <v-btn color="primary" @click="stepNo++">Continue</v-btn>
                    <v-btn text @click="stepNo--">Back</v-btn>
                </v-col>
            </v-stepper-content>
            <v-stepper-step :rules="[() => input.description.length >= lengths.ticket_description.min]"
                            step="5"
                            editable
            >
                Problem description
                <small>
                    Describe your problem
                </small>
            </v-stepper-step>
            <v-stepper-content step="5" class="pa-5">
                <v-col class="pa-2">
                    <v-textarea style="width: 70vh"
                                v-model="input.description"
                                :counter="lengths.ticket_description.max"
                                :rules="[...ticketRules.description]"
                    />
                    <v-btn color="primary" @click="save" :disabled="!valid">Submit</v-btn>
                    <v-btn text @click="stepNo--">Back</v-btn>
                </v-col>
            </v-stepper-content>
        </v-form>
    </v-stepper>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';
    import rules from '@/mixins/rules';

    export default {
        name: "CreateTicket",
        mixins: [rules],
        data() {
            return {
                stepNo: 1,
                input: {
                    title: '',
                    priority: '',
                    users: null,
                    author: null,
                    description: '',
                    autoAssign: true,
                    selfMade: true,
                },
                loading: false,
                items: [
                    {
                        value: 0,
                        display: 'High priority'
                    },
                    {
                        value: 1,
                        display: 'Medium priority'
                    },
                    {
                        value: 2,
                        display: 'Low priority'
                    },
                ],
                valid: false,
            }
        },

        computed: {
            ...mapGetters({
                users: 'members/members',
            }),
        },

        methods: {
            ...mapActions({
                readUsersAction: 'members/readAction',
                createTicketAction: 'tickets/createAction',
            }),

            removeSpecialist(item) {
                this.input.users = null;
            },

            removeAuthor(item) {
                this.input.author = null;
            },

            getUsers() {
                this.loading = true;
                this.readUsersAction(false).then(() => {
                    this.loading = false;
                });
            },

            save() {
                this.createTicketAction(this.input).then(() => {});
            }
        },
    }
</script>

<style scoped>

</style>