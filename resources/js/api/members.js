import axios from '@/plugins/axios.js'

export default {
	getMembers() {
		return axios.get('members')
	},

	getStatusMembers() {
		return axios.get('status-members')
	},

	getPossibleMembers() {
		return axios.get('possible-members')
	},

	removeMember(payload) {
		return axios.delete('members/' + payload.id);
	},

	addMember(payload) {
		return axios.post('members', payload);
	},

	editMember(payload) {
		return axios.put('members/' + payload.id, payload);
	}
}




