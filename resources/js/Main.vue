<template>
    <v-app>
        <router-view></router-view>
        <notification></notification>
    </v-app>
</template>

<script>
    import Notification from "@/components/general/Notification";

    export default {
        name: 'App',
        components: {Notification},
    }
</script>