export default {
    data() {
        return {
            lengths: {
                name: {
                    min: 5,
                    max: 20
                },
                email: {
                    min: 5,
                    max:
                        30
                },
                password: {
                    min: 8
                },
                domain: {
                    min: 2,
                    max: 20
                }
            },
            requiredRules: [
                v => !!v || 'Field is required',
            ],
            roleRules: [
                v => !!v.name || 'Role is required',
            ],
            passwordRequiredRules: [
                v => !!v || 'Password is required',
            ],
            nameRules: [
                v => !!v || 'Full Name is required',
                v => (v || '').length <= this.lengths.name.max || `Full name must be less than ${this.lengths.name.max} characters long`,
                v => (v || '').length >= this.lengths.name.min || `Full name must be more than ${this.lengths.name.min} characters long`,
                v => (v.trim() || '').indexOf(' ') > 0 || 'Please provide user full name'
            ],
            domainRules: [
                v => !!v || 'Domain is required',
                v => (v || '').length <= this.lengths.domain.max || `Domain name must be less than ${this.lengths.domain.max} characters long`,
                v => (v || '').length >= this.lengths.domain.min || `Domain name must be more than ${this.lengths.domain.min} characters long`,
            ],
            emailRules: [
                v => !!v || 'E-mail is required',
                v => (v || '').length <= this.lengths.email.max || `E-mail must be less than ${this.lengths.email.max} characters long`,
                v => (v || '').length >= this.lengths.email.min || `E-mail must be more than ${this.lengths.email.min} characters long`,
                v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
            ],
            passwordRules: [
                v => !!v || 'Password is required',
                v => (v || '').length >= this.lengths.password.min || `Password must be more than ${this.lengths.password.min} characters long`,
                v => (v.trim() || '').indexOf(' ') < 0 || 'No white spaces are allowed'
            ],
            passwordConfirmationRules: [
                v => v === this.input.password || `Password must match`
            ],
        }
    }
}