<template>
    <v-expansion-panels focusable
                        multiple
                        class="mb-6"
    >
        <v-expansion-panel>
            <v-expansion-panel-header color="primary" expand-icon="mdi-menu-down">PENDING TICKETS
            </v-expansion-panel-header>
            <v-expansion-panel-content>
                <v-row>
                    <v-col cols="4" v-for="(ticket, index) in pendingTickets"
                           :key="index"
                    >
                        <v-card style="overflow-y: auto"
                                height="26vh"
                                max-width="344"
                        >
                            <v-list-item>
                                <v-list-item-avatar
                                        :color="ticket.priority === 0 ? 'red' : ticket.priority === 1 ? 'orange' : 'green'">
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <v-list-item-title class="headline">{{ticket.title}}</v-list-item-title>
                                    <v-list-item-subtitle>by {{ticket.created_by.name}} | {{ticket.created_by.email}}
                                    </v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>
                            <v-card-text>
                                {{ticket.description}}
                            </v-card-text>
                            <v-card-actions>
                                <v-btn text color="primary" @click="accept(ticket)">Accept</v-btn>
                                <v-btn text color="primary" @click="edit(ticket)">Edit</v-btn>
                                <v-spacer></v-spacer>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            </v-expansion-panel-content>
        </v-expansion-panel>
        <v-expansion-panel>
            <v-expansion-panel-header color="primary" expand-icon="mdi-menu-down">IN PROGRESS</v-expansion-panel-header>
            <v-expansion-panel-content>
                <v-row>
                    <v-col cols="4" v-for="(ticket, index) in activeTickets"
                           :key="index"
                    >
                        <v-card style="overflow-y: auto"
                                height="26vh"
                                max-width="344"
                        >
                            <v-list-item>
                                <v-list-item-avatar
                                        :color="ticket.priority === 0 ? 'red' : ticket.priority === 1 ? 'orange' : 'green'">
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <v-list-item-title class="headline">{{ticket.title}}</v-list-item-title>
                                    <v-list-item-subtitle>by {{ticket.created_by.name}} | {{ticket.created_by.email}}
                                    </v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>

                            <v-card-text>
                                {{ticket.description}}
                            </v-card-text>

                            <v-card-actions>
                                <v-btn text color="primary" @click="edit(ticket)">Edit</v-btn>
                                <v-btn text color="primary" @click="complete(ticket)">Close</v-btn>
                                <v-spacer></v-spacer>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            </v-expansion-panel-content>
        </v-expansion-panel>
        <v-expansion-panel>
            <v-expansion-panel-header color="primary" expand-icon="mdi-menu-down">COMPLETED TICKETS
            </v-expansion-panel-header>
            <v-expansion-panel-content>
                <v-row>
                    <v-col cols="4" v-for="(ticket, index) in completedTickets"
                           :key="index"
                    >
                        <v-card style="overflow-y: auto"
                                height="26vh"
                                max-width="344"
                        >
                            <v-list-item>
                                <v-list-item-avatar
                                        :color="ticket.priority === 0 ? 'red' : ticket.priority === 1 ? 'orange' : 'green'">
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <v-list-item-title class="headline">{{ticket.title}}</v-list-item-title>
                                    <v-list-item-subtitle>by {{ticket.created_by.name}} | {{ticket.created_by.email}}
                                    </v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>

                            <v-card-text>
                                {{ticket.description}}
                            </v-card-text>

                            <v-card-actions>
                                <v-btn text color="primary" @click="reopen(ticket)">Reopen</v-btn>
                                <v-spacer></v-spacer>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            </v-expansion-panel-content>
        </v-expansion-panel>
        <v-dialog v-if="dialog" max-width="1000px">
            <v-card>
            <v-text-field v-model="task"
                          label="What are you working on?"
                          solo
                          @keydown.enter="create"
            >
                <v-fade-transition v-slot:append>
                    <v-icon v-if="task" @click="create">mdi-add_circle</v-icon>
                </v-fade-transition>
            </v-text-field>
            <h2 class="display-1 success--text pl-4">
                Tasks:&nbsp;
                <v-fade-transition leave-absolute>
        <span :key="`tasks-${tasks.length}`">
          {{ tasks.length }}
        </span>
                </v-fade-transition>
            </h2>
            <v-divider class="mt-4"></v-divider>
            <v-row class="my-1"
                   align="center"
            >
                <strong class="mx-4 info--text text--darken-2">
                    Remaining: {{ remainingTasks }}
                </strong>
                <v-divider vertical/>
                <strong class="mx-4 success--text text--darken-2">
                    Completed: {{ completedTasks }}
                </strong>
                <v-spacer/>
                <v-progress-circular :value="progress"
                                     class="mr-2"
                ></v-progress-circular>
            </v-row>
            <v-divider class="mb-4"/>
            </v-card>
            <v-card v-if="tasks.length > 0">
                <v-slide-y-transition class="py-0"
                                      group
                                      tag="v-list"
                >
                    <template v-for="(task, i) in tasks">
                        <v-divider v-if="i !== 0"
                                   :key="`${i}-divider`"
                        ></v-divider>
                        <v-list-item :key="`${i}-${task.text}`">
                            <v-list-item-action>
                                <v-checkbox v-model="task.done"
                                            :color="task.done && 'grey' || 'primary'"
                                >
                                    <template v-slot:label>
                                        <div :class="task.done && 'grey--text' || 'primary--text'"
                                             class="ml-4"
                                             v-text="task.text"
                                        ></div>
                                    </template>
                                </v-checkbox>
                            </v-list-item-action>
                            <v-spacer/>
                            <v-scroll-x-transition>
                                <v-icon v-if="task.done" color="success">mdi-check</v-icon>
                            </v-scroll-x-transition>
                        </v-list-item>
                    </template>
                </v-slide-y-transition>
            </v-card>
        </v-dialog>
    </v-expansion-panels>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';
    import rules from '@/mixins/rules';
    import Subtasks from "@/components/app/Subtasks";

    export default {
        name: 'Tickets',
        mixins: [rules],
        data: () => ({
            dialog: false,
            now: new Date(),
            editedTicket: null,
            tasks: [],
            task: null,
        }),
        components: {Subtasks},
        computed: {
            ...mapGetters({
                activeTickets: 'tickets/active',
                pendingTickets: 'tickets/pending',
                completedTickets: 'tickets/completed',
            }),
            completedTasks() {
                return this.tasks.filter(task => task.done).length
            },
            progress() {
                return this.completedTasks / this.tasks.length * 100
            },
            remainingTasks() {
                return this.tasks.length - this.completedTasks
            },
        },

        mounted() {
            this.$options.interval = setInterval(this.updateTime, 1000);
            this.initialize();
        },

        methods: {
            ...mapActions({
                readTicketsAction: 'tickets/readAction',
                updateTicketAction: 'tickets/updateAction',
                updateStatusAction: 'tickets/updateStatusAction',
            }),

            create() {
                this.tasks.push({text: this.task, done: false});
                this.task = null;
            },

            updateTime() {
                this.now = new Date();
            },

            initialize() {
                this.loading = true;
                this.readTicketsAction().then(() => {
                    this.loading = false;
                });
            },

            accept(ticket) {
                ticket.status = 1;
                this.updateStatusAction(ticket);
            },

            complete(ticket) {
                ticket.status = 2;
                this.updateStatusAction(ticket);
            },

            reopen(ticket) {
                ticket.status = 1;
                this.updateStatusAction(ticket);
            },

            edit(ticket) {
                this.editedTicket = ticket;
                this.dialog = true;
            },

            save() {
                this.loading = true;
                if (this.editedIndex > -1) {
                    // Update;
                    this.updateTicketAction({
                        index: this.editedIndex,
                        item: this.input
                    }).then(() => {
                        this.now = new Date();
                        this.close();
                        this.loading = false;
                    })
                }
            },
        },
    }
</script>