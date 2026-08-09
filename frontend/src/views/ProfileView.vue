<template>
    <main class="profile-page">

        <section class="profile-header">

            <div class="profile-avatar">
                
                <img
                    v-if="profileUser?.avatarUrl"
                    :src="profileUser.avatarUrl"
                    :alt="profileUser.username"
                >

                <i
                    v-else
                    class="bi bi-person-circle"
                ></i>

            </div>

            <div class="profile-info">

                <div class="profile-identity">

                    <h1>
                        {{ profileUser?.username }}
                    </h1>

                    <button v-if="isMyProfile">
                        Editar perfil
                    </button>

                    <button v-else>
                        Seguir
                    </button>

                </div>

                <h2>
                    {{ profileUser?.name }}
                </h2>

                <p v-if="profileUser?.bio">
                    {{ profileUser.bio }}
                </p>

            </div>

        </section>

        <section class="profile-stats">

            <div class="stat">
                <strong>{{ profileUser?.postsCount ?? 0 }}</strong>
                <span>posts</span>
            </div>

            <div class="stat">
                <strong>{{ profileUser?.followersCount ?? 0 }}</strong>
                <span>seguidores</span>
            </div>

            <div class="stat">
                <strong>{{ profileUser?.followingCount ?? 0 }}</strong>
                <span>seguindo</span>
            </div>

        </section>


        <section class="profile-posts">

            <header class="posts-header">
                <i class="bi bi-grid-3x3"></i>
                <span>Posts</span>
            </header>

            <div class="posts-grid">
                <article
                    v-for="post in posts"
                    :key="post.id"
                    class="profile-post"
                >
                    <img
                        :src="post.mediaUrl"
                        :alt="post.caption"
                    >
                </article>
            </div>

        </section>

    </main>
</template>

<script setup>
import { useAuthStore } from '@/stores/authStore';
import { computed, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import userService from '@/services/userService';

const route = useRoute();
const authStore = useAuthStore();

const username = computed(() => route.params.username);
const profileUser = ref(null);

const posts = ref([]);

const isLoading = ref(false);
const error = ref(null);

const isMyProfile = computed(() => {
    return !username.value ||
        username.value === authStore.user?.username;
});

const loadProfile = async () => {
    if (isMyProfile.value) {
        profileUser.value = authStore.user;
        return;
    }

    profileUser.value = await userService.getUserProfile(username.value);
};

const loadPosts = async () => {
    if (!profileUser.value) {
        return;
    }

    const response = await userService.getUserPosts(
        profileUser.value.username
    );

    posts.value = response.data;
};

onMounted(async () => {
    try {
        isLoading.value = true;

        await loadProfile();
        await loadPosts();

    } catch (e) {
        console.error(e);

        error.value = 'Não foi possível carregar o perfil: ';
    
    } finally {
        isLoading.value = false;
    }
})

</script>

<style scoped>
</style>