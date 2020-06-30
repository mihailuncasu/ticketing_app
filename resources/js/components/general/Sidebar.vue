<template>
    <v-list dense
            shaped
    >
        <v-list-item color="primary"
                     :to="links.dashboard.to"
                     ripple
        >
            <v-list-item-icon>
                <v-icon>{{links.dashboard.icon}}</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
                <v-list-item-title v-text="links.dashboard.title"></v-list-item-title>
            </v-list-item-content>
        </v-list-item>

        <v-divider class="mx-4"></v-divider>

        <v-list-group no-action
                      v-for="(items, menuTitle) in menuItems"
                      :key="menuTitle"
                      color="primary"
        >
            <template v-slot:activator>
                <v-list-item-icon>
                    <v-icon>mdi-home</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title v-text="menuTitle"/>
                </v-list-item-content>
            </template>
            <v-list-item v-for="(item, i) in items" :key="i" :to="item.to">
                <v-list-item-icon>
                    <v-icon>{{ item.icon }}</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title v-text="item.title"/>
                </v-list-item-content>
            </v-list-item>
        </v-list-group>

        <v-divider class="mx-4"></v-divider>

        <v-list-item @click="logout">
            <v-list-item-icon>
                <v-icon v-text="actions.logout.icon"></v-icon>
            </v-list-item-icon>
            <v-list-item-title v-text="actions.logout.title"></v-list-item-title>
        </v-list-item>

        <v-divider class="mx-4" vertical></v-divider>
        <v-spacer></v-spacer>
        <v-list-item>
            <v-switch clipped-right :label="`Dark Theme`" v-model="$vuetify.theme.dark"></v-switch>
        </v-list-item>
    </v-list>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';

    export default {
        computed: {
            ...mapGetters({
                menuItems: 'auth/userMenu'
            }),
        },

        data: () => ({
            isCollapsed: true,
            showMenu: false,
            links: {
                dashboard: {title: 'Home', to: '/', icon: 'mdi-home'},
            },
            actions: {
                logout: {title: 'Logout', icon: 'mdi-power_settings_new'},
            },
        }),

        methods: {
            ...mapActions({
                logoutAction: 'auth/logoutAction',
                profileAction: 'auth/profileAction',
            }),

            logout() {
                this.logoutAction().then(() => {
                    this.$router.push({name: 'login'});
                });
            }
        }
    }
</script>