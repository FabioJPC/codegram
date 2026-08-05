import api from '../api/axios';

export default {
    toggleLike(postId, isCurrentlyliked) {
        if (isCurrentlyliked) {
            return api.delete(`/posts/${postId}/like`)
        } else {
            return api.post(`/posts/${postId}/like`)
        }
    }
}