<template>
    <v-list dense>
        <v-list-item exact :to="links.dashboard.to">
            <v-list-item-icon>
                <v-icon v-text="links.dashboard.icon"></v-icon>
            </v-list-item-icon>
            <v-list-item-title v-text="links.dashboard.title"></v-list-item-title>
        </v-list-item>

        <v-divider class="mx-4"></v-divider>

        <v-list-group prepend-icon="mdi-settings_applications" no-action>
            <template v-slot:activator>
                <v-list-item-content>
                    <v-list-item-title>User management</v-list-item-title>
                </v-list-item-content>
            </template>
            <v-list-item v-for="(admin, i) in admins" :key="i" :to="admin.to">
                <v-list-item-icon>
                    <v-icon>mdi-view-dashboard-variant</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title v-text="admin.title"></v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list-group>

        <v-divider class="mx-4"></v-divider>

       <!-- <v-list-item exact :to="links.theme.to">
            <v-list-item-icon>
                <v-icon v-text="links.theme.icon"></v-icon>
            </v-list-item-icon>
            <v-list-item-title v-text="links.theme.title"></v-list-item-title>
        </v-list-item>-->

        <v-divider class="mx-4" vertical></v-divider>
        <v-spacer></v-spacer>
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
    import {mapActions} from 'vuex';

    export default {
        data: () => ({
            links: {
                dashboard: {title: 'Home', to: '/', icon: 'mdi-dashboard'},
                //theme: {title: 'Theme management', to: '/dashboard/theme', icon: 'color_lens'},
            },
            actions: {
                logout: {title: 'Logout', icon: 'mdi-power_settings_new'}
            },
            admins: [
                {title: 'Users', to: '/admin/users', icon: 'mdi-account_circle'},
                {title: 'Roles', to: '/admin/roles', icon: 'mdi-local_offer'},
                {title: 'Permissions', to: '/admin/permissions', icon: 'mdi-pan_tool'}
            ]
        }),
        methods: {
            ...mapActions({
                logoutAction: 'auth/logoutAction'
            }),

            logout() {
                this.logoutAction().then(() => {
                    this.$router.push({name: 'login'});
                });
            }
        }
    }
</script>