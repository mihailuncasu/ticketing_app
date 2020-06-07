<template>
    <v-app id="inspire" :dark="goDark">
        <v-navigation-drawer v-model="drawer" app clipped>
            <sidebar></sidebar>
        </v-navigation-drawer>

        <v-app-bar app clipped-left>
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"/>
            <v-toolbar-title>Orange ITSM</v-toolbar-title>
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

        <v-snackbar
                bottom
                vertical
                right
                v-model="snackbar.visibility"
                :color="snackbar.color">
                <!--:timeout="timeout"-->

            {{snackbar.message}}
            <v-btn v-if="snackbar.close"
                    dark
                    text
                    @click="closeSnackbar"
            >
                Close
            </v-btn>
        </v-snackbar>

        <v-footer app>
            <span>&copy; 2020</span>
        </v-footer>
    </v-app>
</template>

<script>
    import Sidebar from "@/components/Sidebar";

    export default {
        components: {Sidebar},
        props: {
            source: String,
        },
        data: () => ({
            drawer: null,
            goDark: false,
        }),
        computed: {
            snackbar(){
                return this.$store.getters.responseSnackbar;
            },
            timeout() {
                return this.snackbar.close === false ? 0 : 2000;
            }
        },
        created() {
            // Idk, some theme options;
            this.$vuetify.theme.dark = false;
        },
        methods: {
            closeSnackbar() {
                this.$store.dispatch('closeAction');
            }
        }
    }
</script>