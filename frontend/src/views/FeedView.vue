<template>
    <div class="feed">
        
        <main class="main">
            
            <div class="stories-container">
                <div v-if="storyStore.error" class="error-message">
                    {{ storyStore.error }}
                </div>

                <StoriesBar />
            </div>

            <div class="feed-container">
                <div v-if="feedStore.error" class="error-message">
                    {{ feedStore.error }}
                </div>

                <Post
                    v-for="post in feedStore.posts"
                    :key="post.id"
                    :post="post"
                    @deleted="feedStore.removePost"
                />
            </div>
        </main>

            <article class="suggestions">
                <div class="profile-container">
                    <UserHeader
                        :avatar-url="authStore.user?.avatarUrl"
                        :username="authStore.user.username"
                        :name="authStore.user.name"
                        size="large"
                    />
                </div>

                <div class="follow-sugestions">
                    <h3>Sugestões para você</h3>

                    <p v-if="suggestionStore.error">
                        {{ suggestionStore.error }}
                    </p>

                    <SuggestionCard
                        v-for="user in suggestionStore.suggestions"
                        :key="user.id"
                        :user="user"
                    />

                </div>
            </article>
        </div>

</template>

<script setup>
import Post from '@/components/Post.vue';
import SuggestionCard from '@/components/SuggestionCard.vue';
import StoriesBar from '@/components/StoriesBar.vue';
import { ref, onMounted} from 'vue';
import { useAuthStore } from '@/stores/authStore';
import UserHeader from '@/components/UserHeader.vue';
import { useFeedStore } from '@/stores/feedStore';
import { useSuggestionStore } from '@/stores/suggestionStore';
import { useStoryStore } from '@/stores/storyStore';

const authStore = useAuthStore();
const suggestionStore = useSuggestionStore()
const feedStore = useFeedStore()
const storyStore = useStoryStore()

onMounted(async ()=> {
    if (feedStore.posts.length === 0) {
        await feedStore.loadPosts()
    }
    if (suggestionStore.suggestions.length === 0) {
        suggestionStore.loadSuggestions()
    }
    if (storyStore.stories.length === 0) {
        storyStore.loadStories()
    }
});
</script>

<style scoped>
.feed {
    width: 100%;
    height: 100vh;
    padding: 2px;
    display: flex;
    flex-wrap: nowrap;
    justify-content: flex-start;
    padding-top: 50px;
    padding-bottom: 10px;
}

.stories-container {
    flex: 2 1 0%;
    display: flex;
    justify-content: center;
    width: 100%;
}

.nav {
    position: sticky;
    padding: 20px;
    flex: 2 1 0%;
    display: flex;
    flex-direction: column;
    gap: 70px;
}

.movable {
    display: flex;
    flex: 8 1 0%;
}

.buttons {
    display: flex;
    flex-direction: column;
    gap: 40px;
}

.logo {
    width: 100%;
    height: 15%;
}

.main {
    display: flex;
    flex: 6 1 0%;
    flex-direction: column;
    gap: 40px;
    height: 100%;
    margin-top: 40px;
    overflow-y: auto;
    padding-bottom: 30px;
}
.main::-webkit-scrollbar {
    width: 3px;
}

.main::-webkit-scrollbar-track {
    background-color: transparent;
}

.main::-webkit-scrollbar-thumb {
    background: transparent;
    border-radius: 4px;
    margin: 5px 0;
}

.profile-container {
    color: var(--text-primary);
}

.suggestions {
    flex: 3 1 0%;
    display: flex;
    flex-direction: column;
    gap: 40px;
    margin-top: 8%;
}

.follow-sugestions {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin: 0 15px;
    color: var(--text-tertiary);
}

i {
    font-size: 1.6rem;
    color: var(--text-primary);
    cursor: pointer;
    width: 20px;
}

.profile-avatar {
    border-radius: 50%;
    width: 1.8rem;
}

</style>