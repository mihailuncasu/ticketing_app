<template>
    <div>

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
                            <v-toolbar-title>Forgot password</v-toolbar-title>
                            <v-spacer/>
                        </v-toolbar>
                        <v-card-text>
                            <v-form ref="form"
                                    v-model="valid"
                            >
                                <v-text-field
                                        :rules="rules.email"
                                        label="E-mail"
                                        name="email"
                                        type="email"
                                        v-model="email"
                                />
                            </v-form>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer/>
                            <v-btn @click="sendForgotPassword" :disabled="!valid" color="primary">Send e-mail</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        <router-link :to="{name: 'login'}">Login</router-link>
    </div>
</template>

<script>
    import api from '@/api/auth';

    export default {
        name: "ForgotPassword",

        data() {
            return {
                email: '',
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
                }
            },

            rules() {
                const email = [
                    v => !!v || 'E-mail is required',
                    v => (v || '').length <= this.lengths.email.max || `E-mail must be less than ${this.lengths.email.max} characters`,
                    v => (v || '').length >= this.lengths.email.min || `E-mail must be more than ${this.lengths.email.min} characters`,
                    v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
                ];
                return {
                    email: email,
                }
            }
        },

        methods: {
            sendForgotPassword() {
                if (this.$refs.form.validate()) {
                    api.forgotPassword({
                        email: this.email
                    }).then((result) => {
                        console.log(result);
                    }).catch((error) => {
                        console.log(error)
                    });
                }
            }
        }
    }
</script>

<style scoped>
</style>