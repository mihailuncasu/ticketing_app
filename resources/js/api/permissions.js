import axios from '@/plugins/axios.js'

export default {
	getPermissions() {
		return axios.get('permissions')
	},
	deletePermission(payload) {
		return axios.delete('permissions/' + payload.id);
	},
	savePermission(payload) {
		return axios.post('permissions', payload);
	},
	editPermission(payload) {
		return axios.put('permissions/' + payload.id, payload);
	}
}
