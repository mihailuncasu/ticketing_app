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
                        <v-toolbar-title>Domain login</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-card-text>
                        <v-form ref="form"
                                v-model="valid"
                                @submit.prevent="submit"
                        >
                            <v-text-field v-model="input.domain"
                                          label="Domain"
                                          :counter="lengths.domain.max"
                                          :rules="[...domainRules]"
                                          :error-messages="errors.domain"
                                          suffix="app.websolutions.test"
                            />
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn left text color="primary" :to="{name: 'register'}">Go to register</v-btn>
                        <v-btn right @click="submit" :disabled="!valid" :loading="loading" color="primary">Go to login page</v-btn>
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
        name: 'LoginDomain',
        mixins: [rules],
        data: () => ({
            input: {
                domain: '',
            },
            loading: false,
            //Validation
            valid: false,
            // Errors array from API;
            errors: {},
        }),

        methods: {
            ...mapActions({
                domainLoginAction: 'auth/domainLoginAction'
            }),

            // Always the form submission will call submit;
            submit() {
                // We continue just if the data is valid
                if (this.$refs.form.validate()) {
                    this.loading = true;
                    this.domainLoginAction(this.input).then(({redirect}) => {
                        // Success;
                        this.loading = false;
                        setTimeout(() => window.location.href = redirect, 2000);
                    }).catch(({errors}) => {
                        // Error;
                        this.loading = false;
                        this.valid = false;
                        this.errors = errors;
                        this.$refs.form.reset();
                        this.$refs.form.resetValidation();
                    });
                }
            },
        }
    }
</script>