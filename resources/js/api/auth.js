import axios from '@/plugins/axios.js'

export default {
	login(payload) {
		return axios.post('login', payload)
	},

	domainLogin(payload) {
		return axios.post('domain-login', payload)
	},

	logout() {
		return axios.post('logout')
	},

	register(payload) {
		return axios.post('register', payload)
	},

	forgotPassword(payload) {
		return axios.post('forgot-password', payload)
	},

	resetPassword(payload) {
		return axios.post('reset-password', payload)
	},

	checkDomain(payload) {
		return axios.post('check-domain', payload)
	},

	emailVerification(payload) {
		return axios.get('email-verification', {
			params: payload
		});
	}
}