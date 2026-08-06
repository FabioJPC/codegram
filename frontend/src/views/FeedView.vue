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
        <div class="movable">
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
        </div>
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
    flex: 6 1 0%;
    height: 100%;
    display: flex;
    flex-direction: column;
        margin-top: 40px;
    overflow-y: auto;
}
.main::-webkit-scrollbar {
    width: 3px;
}

.main::-webkit-scrollbar-track {
    background-color: transparent;
}

.main::-webkit-scrollbar-thumb {
    background: var(--text-primary);
    border-radius: 4px;
    margin: 5px 0;
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