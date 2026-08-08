import api from "@/api/axios";

export default {
    async getSuggestions() {
        const response = await api.get('users/suggestions');

        return response.data.data;
    },

    async getUserProfile(username) {
        const response = await api.get(`users/${username}`);

        return response.data.data;
    },

    async getUserPosts(username, page = 1){
        const response = await api.get(`users/${username}/posts?page=${page}`);

        return response.data;
    }
}