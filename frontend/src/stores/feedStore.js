import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { getFeed } from '@/services/feedService'

export const useFeedStore = defineStore('feed', () => {

    const posts = ref([])
    const currentPage = ref(1)
    const lastPage = ref(null)

    const hasMore = computed(() => {
        return lastPage.value === null
            || currentPage.value <= lastPage.value
    })

    const loadPosts = async () => {
        try {
            const response = await getFeed(currentPage.value);
            posts.value = response;
        } catch (e) {
            console.log(e);
        }
    }

    const removePost = (postId) => {
        posts.value = posts.value.filter(post => post.id !== postId);
    }

    const $reset = () => {
        posts.value = [];
        currentPage.value = 1;
        lastPage.value = null;
    }

    return {
        posts,
        currentPage,
        lastPage,
        hasMore,
        loadPosts,
        removePost,
        $reset
    }
})