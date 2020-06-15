<template>
    <v-snackbar :key="notification.timeout"
                @input="clearNotificationAction"
                bottom
                vertical
                right
                :value="notification.visibility"
                :color="notification.color"
                :timeout="notification.timeout"
    >
        <v-card-text class="text-center">
            {{notification.message}}
        </v-card-text>
        <v-progress-linear v-if="notification.loading"
                           indeterminate
                           class="mb-0"
        ></v-progress-linear>
        <v-btn v-else
               dark
               text
               @click="clearNotificationAction"
        >
            Close
        </v-btn>
    </v-snackbar>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';

    export default {
        name: "Notification",

        created() {

        },

        computed: {
            ...mapGetters({
                notification: 'application/notification',
            }),
        },

        methods: {
            ...mapActions({
                clearNotificationAction: 'application/clearNotificationAction'
            }),
        }
    }
</script>
