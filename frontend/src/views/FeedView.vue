<template>
    <div class="feed">
        <nav class="nav">
            <div class="logo">
                <Logo/>
            </div>
            <div class="buttons">
                <i class="bi bi-house-door-fill"></i>
                <i class="bi bi-search"></i>
                <i class="bi bi-plus-circle" @click="showModal = true"></i>
                <i class="bi bi-person-circle"></i>
            </div>
        </nav>
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
                    <Post
                        v-for="post in posts"
                        :key="post.id"
                        :post="post"
                    />
            </div>

        </main>
        <article class="suggestions">
            <div class="profile-container">
                
            </div>
        </article>

        <CreatePostModal
            :open="showModal"
            @close="showModal = false"
        />

    </div>
</template>

<script setup>
import Logo from '@/components/Logo.vue';
import Post from '@/components/Post.vue';
import CreatePostModal from '@/components/CreatePostModal.vue';
import { getFeed } from '@/services/feedService';
import { ref, onMounted} from 'vue';

const posts = ref([]);
const showModal = ref(false);

onMounted(async ()=> {
    const response = await getFeed()
    posts.value = response.data;
});

function openCreatePostModal() {
    showModal.value = true;
}

</script>

<style scoped>
.feed {
    width: 100%;
    height: 100%;
    padding: 2px;
    display: flex;
    flex-wrap: nowrap;
    justify-content: flex-start;
}

.nav {
    position: sticky;
    padding: 20px;
    flex: 2 1 0%;
    display: flex;
    flex-direction: column;
    gap: 70px;
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
    flex: 6 1 0%;
}

.stories-container {
    display: flex;
    gap: 30px;
    height: 20%;
    align-items: center;
    justify-content: center;

    .placeholders {
        height: 90px;
        width: 90px;
        border-radius: 50%;
        background-color: azure;
    }
}

.suggestions {
    flex: 3 1 0%;
}

i {
    font-size: 1.6rem;
    color: var(--text-primary);
}

</style>