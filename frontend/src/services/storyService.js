import api from "@/api/axios";

export default {
    async getFeed() {
        const response = await api.get('/stories/feed');

        return response.data.data;
    },

    async create(file) {
        const formData = new FormData();

        formData.append('image', file);

        const response = await api.post('/stories', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        return response.data;
    },

    async remove(storyId) {
        return api.delete(`/stories/${storyId}`);
    },
}
