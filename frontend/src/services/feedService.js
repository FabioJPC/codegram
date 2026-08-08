import api from "@/api/axios";

export async function getFeed(page = 1) {
    try {
        const response = await api.get(`/feed?page=${page}`);
        return response.data;
    } catch (error) {
        console.log(error.response);
    }
}
