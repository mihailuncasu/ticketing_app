import axios from '@/plugins/axios.js'

export default {
	getGroups() {
		return axios.get('groups');
	},

	deleteGroup(payload) {
		return axios.delete('groups/' + payload.id);
	},

	saveGroup(payload) {
		return axios.post('groups', payload);
	},

	editGroup(payload) {
		return axios.put('groups/' + payload.id, payload);
	}
}
