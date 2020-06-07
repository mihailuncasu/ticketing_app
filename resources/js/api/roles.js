import axios from '@/plugins/axios.js'

export default {

	getRoles() {
		return axios.get('roles')
	},
}
