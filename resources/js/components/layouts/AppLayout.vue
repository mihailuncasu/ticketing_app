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
    import {mapActions} from "vuex";

    export default {
        components: {Sidebar},
        data: () => ({
            drawer: null,
            goDark: false,
        }),

        methods: {
            ...mapActions({
                profileAction: 'auth/profileAction',
            }),
        },

        async created() {
            // Idk, some theme options
            await this.profileAction().then(() => {
                this.showMenu = true;
            });
            this.$vuetify.theme.dark = false;
        },

        beforeRouteEnter(to, from, next) {
            if (to.params.group_slug !== undefined) {
                console.log(to.params.group_slug);
            }
            next();
        }
    }
</script>