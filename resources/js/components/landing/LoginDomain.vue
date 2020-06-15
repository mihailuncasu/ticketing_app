<template>
    <v-layout align-center justify-center>
        <v-flex xs12 sm8 md4>
            <v-card class="elevation-12">

                <v-toolbar dark color="primary">
                    <v-toolbar-title>Domain Login</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-form ref="form"
                            v-model="valid"
                    >
                        <v-container>
                            <v-row>
                                <v-flex xs12>
                                    <v-text-field v-model="domain"
                                                  :rules="rules.domain"
                                                  label="Domain"
                                                  name="domain"
                                                  suffix=".app.websolutions.test"
                                                  @keydown.enter.prevent="domainLogin"
                                    ></v-text-field>
                                </v-flex>
                            </v-row>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" @click="domainLogin" :disabled="!valid">Go to login page</v-btn>
                        </v-container>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import api from '@/api/auth';

    export default {

        data: () => ({
            domain: '',
            valid: false
        }),

        computed: {
            lengths() {
                return {
                    domain: {
                        min: 2,
                        max: 20
                    },
                }
            },

            rules() {
                const domain = [
                    v => !!v || 'Domain name is required',
                    v => (v || '').length <= this.lengths.domain.max || `Domain name must be less than ${this.lengths.domain.max} characters`,
                    v => (v || '').length >= this.lengths.domain.min || `Domain name must be more than ${this.lengths.domain.min} characters`,
                ];
                return {
                    domain: domain
                }
            }
        },

        methods: {
            domainLogin() {
                if (this.$refs.form.validate()) {
                    api.domainLogin({
                        domain: this.domain
                    }).then(({data}) => {
                        window.location.href = data.redirect;
                    }).catch(({response}) => {
                        // Ask for registering the given domain;
                    })
                }
            }
        }
    }
</script>