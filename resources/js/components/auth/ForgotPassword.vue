<template>
    <v-container fluid
                 fill-height
    >
        <v-row align="center"
               justify="center"
        >
            <v-col cols="12"
                   sm="8"
                   md="4"
            >
                <v-card class="elevation-12">
                    <v-toolbar color="primary"
                               dark
                               flat
                    >
                        <v-toolbar-title>Forgot password</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-card-text>
                        <v-form ref="form"
                                v-model="valid"
                                @submit.prevent="submit"
                        >
                            <v-text-field v-model="input.email"
                                          label="E-mail"
                                          name="email"
                                          :counter="lengths.email.max"
                                          :rules="[...emailRules]"
                            />
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn left text color="primary" :to="{name: 'login'}">Go to login</v-btn>
                        <v-btn right @click="submit" :disabled="!valid" :loading="loading" color="primary">Send e-mail
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import {mapActions} from 'vuex';
    import rules from '@/mixins/rules';

    export default {
        name: "ForgotPassword",
        mixins: [rules],
        data() {
            return {
                input: {
                    email: '',
                },
                defaultInput: {
                    email: '',
                },
                valid: false,
                loading: false
            }
        },

        methods: {
            ...mapActions({
                forgotPasswordAction: 'auth/forgotPasswordAction'
            }),

            submit() {
                if (this.$refs.form.validate()) {
                    this.loading = true;
                    this.forgotPasswordAction(this.input).then(() => {
                        this.loading = false;
                        this.valid = false;
                        // Reset the input;
                        this.input = this.defaultInput;
                        this.$refs.form.resetValidation();
                    });
                }
            },
        }
    }
</script>
