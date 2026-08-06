import api from "@/api/axios";

export async function getFeed(page = 1) {
    try {
        const response = await api.get(`/feed?page=${page}`);
        console.log(response);
        return response.data;
    } catch (error) {
        console.log(error.response);
    }
}
