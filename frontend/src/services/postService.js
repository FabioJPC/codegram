import api from '../api/axios';

export default {

    async create(file, caption) {
        const formData = new FormData();
        
        formData.append('caption', caption);
        formData.append('file', file);
        
        const response = await api.post(`/posts`, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        return response.data;


    },

    toggleLike(postId, isCurrentlyliked) {
        if (isCurrentlyliked) {
            return api.delete(`/posts/${postId}/like`)
        } else {
            return api.post(`/posts/${postId}/like`)
        }
    }
}