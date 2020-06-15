<template>
    <div>
        <v-container
                class="fill-height"
                fluid
        >
            <v-row
                    align="center"
                    justify="center"
            >
                <v-col
                        cols="12"
                        md="4"
                        sm="8"
                >
                    <v-card class="elevation-12">
                        <v-toolbar
                                color="primary"
                                dark
                                flat
                        >
                            <v-toolbar-title>Login form</v-toolbar-title>
                            <v-spacer/>
                        </v-toolbar>
                        <v-card-text>
                            <v-form ref="form"
                                    v-model="valid"
                            >
                                <v-text-field :rules="rules.email"
                                              label="Email"
                                              name="email"
                                              type="email"
                                              v-model="input.email"
                                />

                                <v-text-field id="password"
                                              label="Password"
                                              name="password"
                                              :rules="rules.password"
                                              type="password"
                                              v-model="input.password"
                                />
                                <v-text-field id="password_confirmation"
                                              label="Password Confirmation"
                                              :rules="rules.password_confirmation"
                                              name="password_confirmation"
                                              type="password"
                                              v-model="input.password_confirmation"
                                />
                            </v-form>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer/>
                            <v-btn @click="sendResetPassword" :disabled="!valid" color="primary">Reset password</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>

        <router-link :to="{name: 'login'}">Login</router-link>
    </div>
</template>

<script>
    import api from '@/api/auth';

    export default {
        name: "ResetPassword",

        data() {
            return {
                input: {
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                valid: false
            }
        },

        computed: {
            lengths() {
                return {
                    email: {
                        min: 5,
                        max: 30
                    },
                    password: {
                        min: 8
                    },
                }
            },

            rules() {
                const email = [
                    v => !!v || 'E-mail is required',
                    v => (v || '').length <= this.lengths.email.max || `E-mail must be less than ${this.lengths.email.max} characters`,
                    v => (v || '').length >= this.lengths.email.min || `E-mail must be more than ${this.lengths.email.min} characters`,
                    v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
                ];
                const password = [
                    v => !!v || 'Password is required',
                    v => (v || '').length >= this.lengths.password.min || `Password must be more than ${this.lengths.password.min} characters`,
                    v => (v.trim() || '').indexOf(' ') < 0 || 'No white spaces are allowed'
                ];
                const password_confirmation = [
                    v => !!v || 'Confirm password is required',
                    v => v === this.input.password || `Password must match`
                ];
                return {
                    email: email,
                    password: password,
                    password_confirmation: password_confirmation,
                }
            }
        },

        methods: {
            sendResetPassword() {
                if (this.$refs.form.validate()) {
                    const token = this.$route.query.token;
                    api.resetPassword({
                        ...this.input, token
                    }).then((result) => {
                        console.log(result);
                    }).catch((error) => {
                        console.log(error);
                    });
                }
            },
        }
    }
</script>

<style scoped>
</style>