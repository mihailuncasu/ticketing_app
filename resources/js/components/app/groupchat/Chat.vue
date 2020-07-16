<template>
    <div class="chat-wrapper">
        <div ref="chat"
             class="chat"
        >
            <v-overlay absolute
                       :value="loading"
            >
                <v-progress-circular indeterminate size="64"/>
            </v-overlay>
            <Message v-for="(message, index) in messages"
                     :key="`message-${index}`"
                     :message="message"
                     :owner="message.user_id === user.id"
                     :now="now"
            />
        </div>
        <div class="chat__form">
            <ChatForm/>
        </div>
    </div>
</template>

<script>
    import Message from "@/components/app/groupchat/Message";
    import ChatForm from "@/components/app/groupchat/ChatForm";
    import {mapActions, mapGetters} from 'vuex';

    export default {
        name: "Chat",
        components: {
            Message,
            ChatForm,
        },
        data: () => ({
            loading: false,
            typingUsers: [],
            now: new Date()
        }),

        computed: {
            ...mapGetters({
                messages: 'messages/messages',
                user: 'auth/userDetails'
            }),
        },

        created() {
            this.initialize();
        },

        mounted() {
            this.$refs.chat.scrollTop = this.$refs.chat.scrollHeight;
            this.$options.interval = setInterval(this.updateTime, 1000);
            // orange.group-slug.chat
            Echo.private(`${window.subdomain}.${this.$route.params.group_slug}.chat`)
                .listen('MessageSentEvent', (e) => {
                    this.messages.push(e.message);
                });
        },

        methods: {
            ...mapActions({
                readMessagesAction: 'messages/readAction',
            }),

            updateTime() {
                this.now = new Date();
            },

            initialize() {
                if (!this.messages.length) {
                    this.loading = true;
                    this.readMessagesAction().then(() => {
                        this.loading = false;
                    });
                }
            }
        },

        watch: {
            messages() {
                setTimeout(() => {
                    if (this.$refs.chat) {
                        this.$refs.chat.scrollTop = this.$refs.chat.scrollHeight;
                    }
                }, 0);
            },
        },
    }
</script>

<style scoped>
    .chat-wrapper {
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .chat__form {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 1rem;
        height: 80px;
    }

    .chat {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        bottom: 80px;
        padding: 1rem;
        overflow-y: auto;
        color: #000;
    }

    .chat__typing {
        position: absolute;
        display: flex;
        bottom: 50px;
    }

    .chat__typing-user:not(first-child) {
        margin-left: 15px;
    }
</style>