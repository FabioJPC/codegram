import api from "@/api/axios";

export default {

    async create(file, caption) {
        const formData = new FormData();
        
        formData.append('caption', caption);
        formData.append('images[]', file);

        try {
            const response = await api.post(`/posts`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            return response.data;
        } catch(e){
            console.log('ERRO');
            throw e;
        }
    },

    async toggleLike(postId, isCurrentlyliked) {
        if (isCurrentlyliked) {
            return (await api.delete(`/posts/${postId}/likes`)).data
        } else {
            return (await api.post(`/posts/${postId}/likes`)).data;
        }
    }
}