import api from "@/api/axios";

export default {
    async getSuggestions() {
        const response = await api.get('users/suggestions');

        return response.data.data;
    },

    async getUserProfile(username) {
        const response = await api.get(`users/${username}`);
        
        return response.data;
    },

    async updateProfile(data) {
        const response = await api.post('/me', data)

        return response.data
    },

    async getUserPosts(username, page = 1){
        const response = await api.get(`users/${username}/posts?page=${page}`);

        return response.data;
    },

    async getFollowers(userId) {
        const response = await api.get(`/users/${userId}/followers`)

        return response.data;
    },

    async getFollowing(userId) {
        const response = await api.get(`/users/${userId}/following`)

        return response.data;
    },

    async searchUsers(query) {
        const response = await api.get('/users/search', {
            params: { query }
        });

        return response.data.data;
    }
}