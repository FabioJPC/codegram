import api from "@/api/axios";

export default {
    async getFeed() {
        const response = await api.get('/stories/feed');

        return response.data.data;
    },
}
