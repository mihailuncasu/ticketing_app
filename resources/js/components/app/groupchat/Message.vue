<template>
    <v-row v-if="!owner"
           justify="center"
           no-gutters
           class="align-center"
    >
        <v-avatar>
            <v-img :src="message.user.avatar"/>
        </v-avatar>
        <v-col class="msg-wrapper">

            <v-row no-gutters
                   justify="space-between"
                   class="msg"
            >
                <v-col>
                    <span class="font-weight-bold">{{ message.user.name }} | {{ message.user.email }}</span>
                    <p class="mb-0">
                        {{ message.text }}
                    </p>
                </v-col>
                <v-col cols="auto">
                    <span class="msg__date ml-3">{{ message.created_at | moment("from", now) }}</span>
                </v-col>
            </v-row>
        </v-col>
    </v-row>
    <v-row v-else
           justify="center"
           no-gutters
           class="align-center"
    >
        <v-col class="msg-wrapper">
            <v-row no-gutters
                   justify="space-between"
                   class="msg"
                   :class="{ owner }"
            >
                <v-col>
                    <span class="font-weight-bold">{{ message.user.name }} | {{ message.user.email }}</span>
                    <p class="mb-0">
                        {{ message.text }}
                    </p>
                </v-col>
                <v-col cols="auto">
                    <span class="msg__date ml-3">{{ message.created_at | moment("from", now) }}</span>
                </v-col>
            </v-row>
        </v-col>
        <v-avatar>
            <v-img :src="message.user.avatar"/>
        </v-avatar>
    </v-row>

</template>

<script>
    export default {
        name: "Message",
        props: {
            message: {
                type: Object,
                default: () => {

                },
            },
            owner: {
                type: Boolean,
            },
            now: {
                type: Date
            }
        },
    }
</script>

<style scoped>
    .system {
        margin-bottom: 1rem;
        color: #fff;

    p {
        margin-bottom: 1rem;
    }

    }

    .msg-wrapper {
        display: flex;
        flex-direction: column;
    }

    .msg {
        padding: 1rem;
        width: 60%;
        margin: 1rem 2rem;
        box-shadow: 1px 2px 1px 1px rgba(50, 50, 50, 0.3);
        border-radius: 4px;
        background: #1976d2;
        color: #fff;
        position: relative;
        word-break: break-all;
        margin-bottom: 1rem;

    &
    __date {
        text-decoration: underline;
    }

    }

    .owner {
        background: #fff;
        color: #000;
        align-self: flex-end;
    }

    @media (max-width: 400px) {
        .msg {
            width: 90%;
        }
    }
</style>