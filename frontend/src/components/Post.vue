<template>
    <article class="card">
        <div class="card-header">
            <img 
                v-if="post.author.avatarUrl" 
                :src="post.author.avatarUrl" 
                alt="Avatar" 
                class="avatar-img" 
            />
            <i v-else class="bi bi-person-circle"></i>

            <span class="username">{{ post.author.username }}</span>
        </div>

        <div class="card-content">
            <img :src="post.mediaUrl" :alt="post.mediaCaption" class="post-media">
        </div>

        <div class="card-footer">
            <div class="actions">
                <div class="left-actions">
                    <i 
                        :class="[isLiked ? 'bi-heart-fill liked' : 'bi-heart']"
                        class="bi"
                        @click = 'toggleLike'
                    ></i>
                    <i class="bi bi-chat"></i>
                    <i class="bi bi-send"></i>
                </div>
                <i class="bi bi-bookmark"></i>
            </div>
            <p class="likes"><b>{{ localLikesCount }}</b></p>
        </div>
    </article>
</template>

<script setup>
import { ref } from 'vue';
import postService from '@/services/postService';

const props = defineProps({
    post: {
        type: Object,
        required: true,
        default: () =>  ({
            id: '',
            mediaUrl: '',
            caption: '',
            likesCount: 0,
            isLikedByMe: false,
            author: {
                username: 'usuario',
                avatarUrl: ''
            }
        })
    }
});

const isLiked = ref(props.post.isLikedByMe);
const localLikesCount = ref(props.post.likesCount);

const toggleLike = async () => {
    const previousState = isLiked.value;
    const previousCount = localLikesCount.value;

    isLiked.value = !isLiked.value;
    localLikesCount.value += isLiked.value ? 1 : -1;

    try {
        await postService.toggleLike(props.post.id, isLiked);
    } catch (error) {
        isLiked.value = previousState;
        localLikesCount.value = previousCount;
        // Todo: add visual feedback to user
    }
}

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

.actions {
    font-size: 1.6rem;
    display: flex;
    justify-content: space-between;
}

.left-actions {
    display: flex;
    gap: 15px;;
}

.liked {
    color: red;
}

.post-media {
    width: 100%;
    aspect-ratio: 4/5;
}

</style>