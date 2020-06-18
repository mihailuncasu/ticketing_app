<template>
    <v-container grid-list-md style="width: 1000px">
        <v-card
                class="mt-4"
                max-width="600"
        >
            <v-sheet
                    class="v-sheet--offset mx-auto"
                    color="cyan"
                    elevation="12"
                    max-width="calc(100% - 32px)"
            >
                <v-sparkline
                        :labels="labels"
                        :value="value"
                        color="black"
                        line-width="2"
                        padding="16"
                ></v-sparkline>
            </v-sheet>

            <v-card-text class="pt-0">
                <div class="title font-weight-light mb-2">User Registrations</div>
                <div class="subheading font-weight-light grey--text">Last Campaign Performance</div>
                <v-divider class="my-2"></v-divider>
                <v-icon
                        class="mr-2"
                        small
                >
                    mdi-clock
                </v-icon>
                <span class="caption grey--text font-weight-light">last registration 26 minutes ago</span>
            </v-card-text>
        </v-card>
        <v-spacer></v-spacer>
        <v-divider></v-divider>
        <v-text-field
                v-model="task"
                label="What are you working on?"
                solo
                @keydown.enter="create"
        >
            <v-fade-transition v-slot:append>
                <v-icon
                        v-if="task"
                        @click="create"
                >
                    mdi-add_circle
                </v-icon>
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

        <v-row
                class="my-1"
                align="center"
        >
            <strong class="mx-4 info--text text--darken-2">
                Remaining: {{ remainingTasks }}
            </strong>

            <v-divider vertical></v-divider>

            <strong class="mx-4 success--text text--darken-2">
                Completed: {{ completedTasks }}
            </strong>

            <v-spacer></v-spacer>

            <v-progress-circular
                    :value="progress"
                    class="mr-2"
            ></v-progress-circular>
        </v-row>

        <v-divider class="mb-4"></v-divider>

        <v-card v-if="tasks.length > 0">
            <v-slide-y-transition
                    class="py-0"
                    group
                    tag="v-list"
            >
                <template v-for="(task, i) in tasks">
                    <v-divider
                            v-if="i !== 0"
                            :key="`${i}-divider`"
                    ></v-divider>

                    <v-list-item :key="`${i}-${task.text}`">
                        <v-list-item-action>
                            <v-checkbox
                                    v-model="task.done"
                                    :color="task.done && 'grey' || 'primary'"
                            >
                                <template v-slot:label>
                                    <div
                                            :class="task.done && 'grey--text' || 'primary--text'"
                                            class="ml-4"
                                            v-text="task.text"
                                    ></div>
                                </template>
                            </v-checkbox>
                        </v-list-item-action>

                        <v-spacer></v-spacer>

                        <v-scroll-x-transition>
                            <v-icon
                                    v-if="task.done"
                                    color="success"
                            >
                                mdi-check
                            </v-icon>
                        </v-scroll-x-transition>
                    </v-list-item>
                </template>
            </v-slide-y-transition>
        </v-card>
    </v-container>
</template>

<script>
    export default {
        data: () => ({
            tasks: [],
            task: null,
            labels: [
                '12am',
                '3am',
                '6am',
                '9am',
                '12pm',
                '3pm',
                '6pm',
                '9pm',
            ],
            value: [
                200,
                675,
                410,
                390,
                310,
                460,
                250,
                240,
            ],
        }),

        computed: {
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

        methods: {
            create() {

            },
        },
    }
</script>

<style>
    .v-sheet--offset {
        top: -24px;
        position: relative;
    }
</style>