<template>
    <v-container fluid  height="81vh">
        <v-card>
            <v-toolbar color="primary"

                       flat
            >
                <v-toolbar-title>Group Chat</v-toolbar-title>
                <v-spacer></v-spacer>
            </v-toolbar>
            <v-card-text>
                <v-row no-gutters>
                    <v-col cols="15"
                    >
                        <Chat/>
                    </v-col>
                    <v-col cols="3"
                    >
                        <GroupMembers/>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
    </v-container>
</template>

<script>
    import GroupMembers from "@/components/app/groupchat/GroupMembers";
    import Chat from "@/components/app/groupchat/Chat";

    export default {
        name: "GroupChat",
        components: {
            GroupMembers,
            Chat
        },

        data: () => ({

        }),
    }
</script>

<style scoped>

</style>