import axios from '@/plugins/axios.js'

export default {
	getMessages() {
		return axios.get('messages');
	},
	saveMessage(payload) {
		return axios.post('messages', payload);
	},
}
