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
    },

    async getComments(postId) {
        try {
            const response = await api.get(`/posts/${postId}/comments`);
            return response.data;
        
        } catch (e) {
            console.log(e);
        }

    },

    async createComment(postId, comment) {
        const response = await api.post(
            `/posts/${postId}/comments`,
            {
                comment: comment
            }
        );

        return response.data;
    },
    
    async updateComment(commentId, comment) {
        const response = await api.patch(
            `/comments/${commentId}`,
            {
                comment: comment
            }
        );

        return response.data;
    },

    async deleteComment(commentId) {
        return api.delete(`/comments/${commentId}`);
    },

    async getPost(postId) {
        const response = await api.get(`/posts/${postId}`);

        return response.data;
    },

    async deletePost(postId) {
        return api.delete(`/posts/${postId}`);
    }
}