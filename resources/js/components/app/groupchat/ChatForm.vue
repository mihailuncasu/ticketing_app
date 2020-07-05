<template>
    <v-form ref="form"
            @submit.prevent="send"
    >
        <v-text-field v-model="input.text"
                      label="Message"
                      outlined
                      :rules="[...requiredTextRules]"
                      append-icon="mdi-send-circle-outline"
                      :loading="loading"
                      @click:append="send"
                      @blur="resetValidation"
                      @input="typing"
        />
    </v-form>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';
    import rules from '@/mixins/rules';

    export default {
        name: "ChatForm",
        mixins: [rules],
        data: () => ({
            input: {
                text: ""
            },
            loading: false,
        }),

        computed: {},

        methods: {
            ...mapActions({
                createMessageAction: 'messages/createAction',
            }),

            send() {
                if (this.$refs.form.validate()) {
                    this.loading = true;
                    this.createMessageAction(this.input).then(() => {
                        this.input.text = "";
                        this.loading = false;
                        this.resetValidation();
                    });
                }
            },

            resetValidation() {
                this.$refs.form.resetValidation();
            },

            typing() {
                console.log('i am typing');
            },
        },
    }
</script>

<style scoped>

</style>