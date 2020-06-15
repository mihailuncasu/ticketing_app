<template>
    <v-app id="inspire" :dark="goDark">
        <v-navigation-drawer v-model="drawer" app clipped>
            <sidebar></sidebar>
        </v-navigation-drawer>

        <v-app-bar app clipped-left>
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"/>
            <v-toolbar-title>ITSM</v-toolbar-title>
        </v-app-bar>

        <v-content>
            <v-container fluid fill-height>
                <v-layout justify-center>
                    <v-flex shrink>
                        <router-view></router-view>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>

        <v-footer app>
            <span>&copy; 2020</span>
        </v-footer>
    </v-app>
</template>

<script>
    import Sidebar from "@/components/general/Sidebar";

    export default {
        components: {Sidebar},
        data: () => ({
            drawer: null,
            goDark: false,
        }),

        created() {
            // Idk, some theme options;
            this.$vuetify.theme.dark = false;
        },
    }
</script>