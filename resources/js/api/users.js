import axios from '@/plugins/axios.js'

export default {

	getUsers() {
		return axios.get('users')
	},
}
