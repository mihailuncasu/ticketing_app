import axios from '@/plugins/axios.js'

export default {
    getTickets() {
        return axios.get('tickets')
    },

    getTicket(payload) {
        return axios.get('tickets/' + payload.id)
    },

    saveTicket(payload) {
        return axios.post('tickets', payload)
    },

    editTicket(payload) {
        return axios.put('tickets' + payload.id, payload)
    },

    deleteTicket(payload) {
        return axios.delete('tickets' + payload.id)
    }
}
