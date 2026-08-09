<template>
    <div class="feed">
        
        <main class="main">
            <div class="stories-container">
                <div class="placeholders"></div>
                <div class="placeholders"></div>
                <div class="placeholders"></div>
                <div class="placeholders"></div>
                <div class="placeholders"></div>
                <div class="placeholders"></div>
            </div>

            <div class="feed-container">
                <div v-if="error" class="error-message">
                    {{ error }}
                </div>

                <Post
                    v-for="post in posts"
                    :key="post.id"
                    :post="post"
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

                    <p v-if="suggestionError">
                        {{ suggestionError }}
                    </p>

                    <SuggestionCard
                        v-for="user in suggestions"
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
import { getFeed } from '@/services/feedService';
import { ref, onMounted} from 'vue';
import { useAuthStore } from '@/stores/authStore';
import userService from '@/services/userService';
import UserHeader from '@/components/UserHeader.vue';

const posts = ref([]);
const suggestions = ref([]);

const isLoading = ref(false);

const postError = ref(null);
const suggestionError = ref(null)

const authStore = useAuthStore();

const loadSuggestions = async () => {
    try {
        suggestionError.value = null;

        suggestions.value = await userService.getSuggestions();
    } catch (error) {
        suggestionError.value = 'Erro ao carregar sugestões.';
    }
}

const loadFeed = async () => {
    try {
        isLoading.value = true;
        postError.value = null;

        const response = await getFeed();

        posts.value = response || []
    } catch (error) {
        postError.value = 'Não foi possível carregar o feed.';
    } finally {
        isLoading.value = false;
    }
}

onMounted(async ()=> {
    await loadFeed();
    await loadSuggestions();
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

.stories-container {
    display: flex;
    gap: 30px;
    height: 20%;
    align-items: center;
    justify-content: center;
    margin-top: 40px;

    .placeholders {
        height: 90px;
        width: 90px;
        border-radius: 50%;
        background-color: azure;
    }
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