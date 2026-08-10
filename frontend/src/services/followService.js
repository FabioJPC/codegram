import api from "@/api/axios"
export default {
    async follow(userId) {
        const response = await api.post(`/users/${userId}/follow`)

        return response.data
    },

    async unfollow(userId) {
        const response = await api.delete(`/users/${userId}/follow`)

        return response.data
    }
}