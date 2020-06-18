<template>
    <v-container fill-height
                 fluid
    >
        <v-row align="center"
               justify="center"
        >
            <v-col cols="12"
                   md="4"
                   sm="8"
            >
                <v-card class="elevation-12">
                    <v-toolbar color="primary"
                               dark
                               flat
                    >
                        <v-toolbar-title>Reset password</v-toolbar-title>
                        <v-spacer/>
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

                            <v-text-field v-model="input.password"
                                          label="Password"
                                          :rules="[...passwordRules]"
                                          type="password"
                            />
                            <v-text-field v-model="input.password_confirmation"
                                          label="Password Confirm"
                                          :rules="[...passwordConfirmationRules]"
                                          type="password"
                            />
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer/>
                        <v-btn left text color="primary" :to="{name: 'login'}">Go to login</v-btn>
                        <v-btn @click="submit" :disabled="!valid" :loading="loading" color="primary">Reset password
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
        name: "ResetPassword",
        mixins: [rules],
        data() {
            return {
                input: {
                    email: '',
                    password: '',
                    password_confirmation: '',
                    token: ''
                },
                defaultInput: {
                    email: '',
                    password: '',
                    password_confirmation: '',
                    token: ''
                },
                valid: false,
                loading: false
            }
        },

        methods: {
            ...mapActions({
                resetPasswordAction: 'auth/resetPasswordAction'
            }),

            submit() {
                if (this.$refs.form.validate()) {
                    this.loading = true;
                    this.input.token = this.$route.query.token;
                    this.resetPasswordAction(this.input).then(({redirect}) => {
                        this.loading = false;
                        this.$router.push({name: redirect});
                    }).catch(() => {
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
