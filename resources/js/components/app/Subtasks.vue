<template>

</template>

<script>
    export default {
        data: () => ({
            tasks: [],
            task: null,
        }),

        props: {
            ticket: Object,
        },

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
                this.tasks.push({text: this.task, done: false});
                this.task = null;
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