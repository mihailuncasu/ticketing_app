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
                    max: 30
                },
                password: {
                    min: 8
                },
                domain: {
                    min: 2,
                    max: 20
                },
                role: {
                    max: 20,
                    min: 3
                },
                permission: {
                    max: 20,
                    min: 3
                },
                group_description: {
                    max: 300,
                    min: 5
                },
                group_name: {
                    max: 30,
                    min: 3
                }
            },
            requiredRules: [
                v => !!v || 'Field is required',
            ],
            requiredRoleRules: [
                v => !!v.name || 'Role is required',
            ],
            roleRules: [
                v => !!v || 'Role name is required',
                v => (v || '').length <= this.lengths.role.max || `Role name must be less than ${this.lengths.role.max} characters`,
                v => (v || '').length >= this.lengths.role.min || `Role name must be more than ${this.lengths.role.min} characters`,
            ],
            groupNameRules: [
                v => !!v || 'Group name is required',
                v => (v || '').length <= this.lengths.group_name.max || `Group description must be less than ${this.lengths.group_name.max} characters`,
                v => (v || '').length >= this.lengths.group_name.min || `Group description must be more than ${this.lengths.group_name.min} characters`,
            ],
            groupDescriptionRules: [
                v => !!v || 'Group description is required',
                v => (v || '').length <= this.lengths.group_description.max || `Group description must be less than ${this.lengths.group_description.max} characters`,
                v => (v || '').length >= this.lengths.group_description.min || `Group description must be more than ${this.lengths.group_description.min} characters`,
            ],
            groupManagersRules: [
                v => !!v.length || 'The group needs at least one manager',
            ],
            permissionRules: [
                v => !!v || 'Role name is required',
                v => (v || '').length <= this.lengths.permission.max || `Role name must be less than ${this.lengths.permission.max} characters`,
                v => (v || '').length >= this.lengths.permission.min || `Role name must be more than ${this.lengths.permission.min} characters`,
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