import axios from '@/plugins/axios.js'

export default {

	getUsers() {
		return axios.get('users')
	},
	deleteUser(payload) {
		return axios.delete('users/' + payload.id);
	},
	saveUser(payload) {
		return axios.post('users', payload);
	},
	editUser(payload) {
		return axios.put('users/' + payload.id, payload);
	}
}
