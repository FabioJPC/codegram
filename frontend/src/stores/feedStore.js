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

    return {
        posts,
        currentPage,
        lastPage,
        hasMore,
        loadPosts
    }
})