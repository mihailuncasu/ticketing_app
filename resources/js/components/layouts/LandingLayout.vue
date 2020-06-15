<template>
    <v-content>
        <nav-bar></nav-bar>
        <v-content>
            <v-container class="fill-height"
                         fluid
            >
                <router-view></router-view>
            </v-container>
        </v-content>
        <v-footer app>
        </v-footer>
    </v-content>
</template>

<script>
    import NavBar from "@/components/general/NavBar";

    export default {
        components: {NavBar},
        data: () => ({}),
    }
</script>