import axios from '@/plugins/axios.js'

export default {

	getRoles() {
		return axios.get('roles')
	},
	deleteRole(payload) {
		return axios.delete('roles/' + payload.id);
	},
	saveRole(payload) {
		return axios.post('roles', payload);
	},
	editRole(payload) {
		return axios.put('roles/' + payload.id, payload);
	}
}
