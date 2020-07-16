<template>
    <v-card max-width="300"
            class="overflow-y-auto mx-auto pa-2"
            outlined
            tile
            height="69vh"
    >
        <v-card-text v-if="loading">
            <v-responsive class="mx-auto" v-for="index in [1,2]" :key="index">
                <v-skeleton-loader  type="text" class="mx-auto pa-2"/>
                <v-skeleton-loader  type="heading" class="mx-auto  pa-2"/>
                <v-skeleton-loader  type="heading" class="mx-auto  pa-2"/>
                <v-skeleton-loader  type="heading" class="mx-auto  pa-2"/>
                <v-skeleton-loader  type="heading" class="mx-auto  pa-2"/>
                <v-skeleton-loader  type="heading" class="mx-auto  pa-2"/>
            </v-responsive>
        </v-card-text>
        <v-card-text v-else>
            <v-list subheader>
                <v-subheader>Active members</v-subheader>

                <v-list-item
                        v-for="member in onlineMembers"
                        :key="member.name"
                >
                    <v-list-item-avatar>
                        <v-img :src="member.avatar"></v-img>
                    </v-list-item-avatar>

                    <v-list-item-content>
                        <v-list-item-title v-text="member.name"></v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>

            <v-divider></v-divider>

            <v-list subheader>
                <v-subheader>Inactive members</v-subheader>

                <v-list-item
                        v-for="member in offlineMembers"
                        :key="member.name"
                >
                    <v-list-item-avatar>
                        <v-img :src="member.avatar"></v-img>
                    </v-list-item-avatar>

                    <v-list-item-content>
                        <v-list-item-title v-text="member.name"></v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-card-text>
    </v-card>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';

    export default {
        name: "GroupMembers",
        data: () => ({
            loading: true
        }),

        computed: {
            ...mapGetters({
                onlineMembers: 'members/onlineMembers',
                offlineMembers: 'members/offlineMembers',
            }),
        },

        methods: {
            ...mapActions({
                readStatusMembers: 'members/readStatusAction',
                addOnlineMember: 'members/addOnlineMember'
            }),
        },

        mounted() {
            this.readStatusMembers().then(() => {
                this.loading = false;
            });

             Echo.private(`${window.subdomain}.login-event`)
                .listen('LoginEvent', (e) => {
                    this.addOnlineMember(e.user);
                });
        }
    }
</script>

<style scoped>

</style>