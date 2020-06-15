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
                        <v-toolbar-title>Register</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-card-text>
                        <v-form ref="form"
                                v-model="valid"
                        >
                            <v-text-field v-model="input.name"
                                          label="Full Name"
                                          name="name"
                                          :counter="lengths.name.max"
                                          :error-messages="errors.name"
                                          :rules="[...nameRules]"
                                          @input="errors.name = []"
                            />
                            <v-text-field v-model="input.email"
                                          label="E-mail"
                                          name="email"
                                          :counter="lengths.email.max"
                                          :error-messages="errors.email"
                                          :rules="[...emailRules]"
                                          @input="errors.email = []"
                            />
                            <v-text-field v-model="input.domain"
                                          label="Domain"
                                          :success="domainCheck.valid"
                                          @input="validateDomain"
                                          :counter="lengths.email.max"
                                          :rules="[...domainRules]"
                                          :error-messages="domainCheck.response"
                                          suffix="app.websolutions.test"
                            >
                                <template v-slot:append>
                                    <v-fade-transition leave-absolute>
                                        <v-progress-circular v-if="domainCheck.loading"
                                                             size="24"
                                                             color="info"
                                                             indeterminate
                                        ></v-progress-circular>
                                        <v-icon v-else-if="!domainCheck.valid && domainCheck.response"
                                                color="error">close
                                        </v-icon>
                                        <v-icon v-else-if="domainCheck.valid" color="success">check</v-icon>
                                    </v-fade-transition>
                                </template>
                            </v-text-field>
                            <v-text-field v-model="input.password"
                                          label="Password"
                                          :rules="[...passwordRules]"
                                          :error-messages="errors.password"
                                          @input="errors.password = []"
                                          type="password"
                            />
                            <v-text-field v-model="input.password_confirmation"
                                          label="Password Confirm"
                                          :rules="[...passwordConfirmationRules]"
                                          :error-messages="errors.password_confirmation"
                                          @input="errors.password_confirmation = []"
                                          type="password"
                            />
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn @click="submit"
                               :disabled="!valid"
                               :loading="loading"
                               color="primary"
                        >
                            Register
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import api from '@/api/auth';
    import {mapActions} from 'vuex';
    import rulesMixin from '@/mixins/rulesMixin';

    export default {
        name: 'Register',
        mixins: [rulesMixin],
        data: () => ({
            input: {
                name: '',
                email: '',
                domain: '',
                password: '',
                password_confirmation: ''
            },
            loading: false,
            //Validation
            valid: false,
            // Errors array from API;
            errors: {},
            domainCheck: {
                valid: false,
                error: '',
                loading: false
            }
        }),

        methods: {
            ...mapActions({
                registerAction: 'auth/registerAction'
            }),

            // Always the form submission will call submit;
            submit() {
                // We continue just if the data is valid
                if (this.$refs.form.validate()) {
                    this.loading = true;
                    this.registerAction(this.input).then(({redirect}) => {
                        // Success;
                        this.loading = false;
                        this.$router.push({name: redirect});
                    }).catch(({errors}) => {
                        // Error;
                        this.loading = false;
                        this.valid = false;
                        this.errors = errors;
                    });
                }
            },

            async validateDomain() {
                this.domainCheck.loading = true;
                await api.checkDomain({
                    domain: this.input.domain
                }).then(({data}) => {
                    this.domainCheck.valid = data.valid;
                    this.domainCheck.response = '';
                }).catch(({response}) => {
                    this.domainCheck.response = response.data.error;
                    this.domainCheck.valid = response.data.valid;
                });
                this.domainCheck.loading = false;
            }
        },
    }
</script>
