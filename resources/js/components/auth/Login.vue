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
                        <v-toolbar-title>Login</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-card-text>
                        <v-form ref="form"
                                v-model="valid"
                        >
                            <v-text-field v-model="input.email"
                                          label="E-mail"
                                          name="email"
                                          :counter="lengths.email.max"
                                          :error-messages="errors.email"
                                          :rules="[...emailRules]"
                                          @input="errors.email = []"
                            />
                            <v-text-field v-model="input.password"
                                          label="Password"
                                          :rules="[...passwordRequiredRules]"
                                          :error-messages="errors.password"
                                          @input="errors.password = []"
                                          type="password"
                            />
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn left
                               text
                               color="primary"
                               :to="{name: 'forgotPassword'}"
                        >
                            Forgot Password?
                        </v-btn>
                        <v-btn right @click="submit" :loading="loading" :disabled="!valid" color="primary">Login</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import {mapActions} from 'vuex';
    import rulesMixin from '@/mixins/rulesMixin';

    export default {
        name: 'Login',
        mixins: [rulesMixin],
        data: () => ({
            input: {
                email: '',
                password: '',
            },
            valid: false,
            errors: {},
            loading: false
        }),
        methods: {
            ...mapActions({
                loginAction: 'auth/loginAction'
            }),

            submit() {
                if (this.$refs.form.validate()) {
                    this.loading = true;
                    this.loginAction(this.input).then(({redirect}) => {
                        // Success;
                        this.loading = false;
                        //this.$router.push({name: redirect});
                    }).catch(({errors}) => {
                        // Error;
                        this.loading = false;
                        this.valid = false;
                        this.$refs.form.reset();
                        if (errors) {
                            this.errors = errors;
                        }
                    });
                }
            }
        }
    }
</script>