import api from "@/api/axios";

export default {

    async create(file, caption) {
        console.log('create')
        const formData = new FormData();
        
        formData.append('caption', caption);
        formData.append('images[]', file);
        
        console.log('criação form')
        try {
            const response = await api.post(`/posts`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            console.log(response.data);
            return response.data;
        } catch(e){
            console.log('ERRO');
            console.log(e);
            console.log(e.response.data);
            console.log(e.response.status);
            throw e;
        }
    },

    toggleLike(postId, isCurrentlyliked) {
        if (isCurrentlyliked) {
            return api.delete(`/posts/${postId}/like`)
        } else {
            return api.post(`/posts/${postId}/like`)
        }
    }
}