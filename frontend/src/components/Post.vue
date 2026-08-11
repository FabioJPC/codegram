<template>
    <article class="card">
        <div class="card-header">
            <UserHeader 
                :avatar-url="post.author.avatarUrl"
                :clickable="true"
                :name="post.author.name"
                :username="post.author.username"
            />
        </div>

        <div 
            class="card-content"
        >
            <PostMedia 
                :images="post.images"
                @click="openPostDetails" 
            />
        </div>

        <div class="card-footer">
            <PostActions
                :comments-count="localCommentsCount"
                :is-liked="isLiked"
                :local-likes-count="localLikesCount"
                @like = 'toggleLike'
                @comments = 'openPostDetails'

            />
            <div class="caption"> {{ post.caption }}</div>

        </div>

        <PostDetailModal
            :open="showPostDetails"
            :post="post"
            :likesCount="localLikesCount"
            :commentsCount="localCommentsCount"
            :is-liked="isLiked"
            :full-height="true"
            @close="closePostDetails"
            @like="toggleLike"
            @comment-created="incrementCommentsCount"
            @comment-deleted="decrementCommentsCount"
            @deleted="$emit('deleted', post.id)"
        />
    </article>
</template>

<script setup>
import { ref } from 'vue';
import postService from '@/services/postService';
import PostMedia from './PostMedia.vue';
import PostActions from './PostActions.vue';
import PostDetailModal from './PostDetailModal.vue';
import UserHeader from './UserHeader.vue';

const emit = defineEmits(['open-details', 'deleted']);

const props = defineProps({
    post: {
        type: Object,
        required: true,
        default: () =>  ({
            id: '',
            mediaUrl: '',
            caption: '',
            likesCount: 0,
            commentsCount: 0,
            isLikedByMe: false,
            author: {
                username: 'usuario',
                avatarUrl: ''
            }
        })
    }
});

const showPostDetails = ref(false);

const openPostDetails = () => {
    showPostDetails.value = true;
};

const closePostDetails = () => {
    showPostDetails.value = false;
};

const isLiked = ref(props.post.isLikedByMe);
const localLikesCount = ref(props.post.likesCount);

const localCommentsCount = ref(props.post.commentsCount);

const toggleLike = async () => {
    const previousState = isLiked.value;
    const previousCount = localLikesCount.value;

    isLiked.value = !isLiked.value;
    localLikesCount.value += isLiked.value ? 1 : -1;

    try {
        const response = await postService.toggleLike(props.post.id, previousState);

        isLiked.value = response.data.isLikedByMe;
        localLikesCount.value = response.data.likesCount;

    } catch (error) {
        isLiked.value = previousState;
        localLikesCount.value = previousCount;
    }
}
const incrementCommentsCount = () => {
    localCommentsCount.value++;
};

const decrementCommentsCount = () => {
    if (localCommentsCount.value > 0) {
        localCommentsCount.value--;
    }
};

</script>

<style scoped>
.card {
    width: 80%;
    max-width: 470px;
    margin: 0 auto 24px auto;
    border: 1px solid #dbdbdb;
    border-radius: 8px;
    background-color: #ffffff;
    overflow: hidden;
}

.post-media {
    width: 100%;
    aspect-ratio: 4/5;
    object-fit: cover;
}

.card-header {
    max-height: 50px;
    padding: 0;
}

</style>