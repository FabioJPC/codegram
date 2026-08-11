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

            <div class="profile-content">

                <div class="profile-identity">

                    <h1>
                        {{ profileUser?.username }}
                    </h1>

                    <button
                        v-if="isMyProfile"
                        class="profile-button"
                        @click="editProfile"
                    >
                        Editar perfil
                    </button>

                    <button
                        v-if="isMyProfile"
                        class="profile-button"
                        @click="logout"
                    >
                        Sair
                    </button>

                    <FollowButton
                        v-if="profileUser && !isMyProfile"
                        :is-following="followStore.isFollowing(profileUser.id)"
                        :loading="followStore.isLoading(profileUser.id)"
                        @toggle-follow="toggleFollow"
                    />

                </div>

                <div class="profile-stats">

                    <div class="stat">
                        <strong>
                            {{ profileUser?.postsCount ?? 0 }}
                        </strong>

                        <span>
                            {{ profileUser?.postsCount === 1 ? 'publicação' : 'publicações' }}
                        </span>
                    </div>

                    <button
                        class="stat"
                        @click="openFollowers"
                    >
                        <strong>
                            {{ followStore.followersCount(profileUser?.id) }}
                        </strong>

                        <span>
                            seguidores
                        </span>
                    </button>

                    <button
                        class="stat"
                        @click="openFollowing"
                    >
                        <strong>
                            {{ isMyProfile 
                                ? followStore.myFollowingCount 
                                : profileUser?.followingCount ?? 0 }}
                        </strong>

                        <span>
                            seguindo
                        </span>
                    </button>
                </div>

                <div class="profile-description">
                    <strong class="profile-name">
                        {{ profileUser?.name }}
                    </strong>

                    <p
                        v-if="profileUser?.bio"
                        class="profile-bio"
                    >
                        {{ profileUser.bio }}
                    </p>
                </div>
            </div>
        </section>

        <nav class="profile-tabs">

            <button
                class="profile-tab active"
            >
                <i class="bi bi-grid-3x3"></i>

                <span>
                    PUBLICAÇÕES
                </span>
            </button>

        </nav>

        <section class="profile-posts">

            <div
                v-if="posts.length"
                class="posts-grid"
            >
                <article
                    v-for="post in posts"
                    :key="post.id"
                    class="profile-post"
                    @click="openPost(post)"
                >
                    <img
                        v-if="post.images?.length"
                        :src="post.images[0].url"
                        :alt="post.caption"
                    >

                    <i
                        v-if="post.images?.length > 1"
                        class="bi bi-copy multiple-images"
                    ></i>

                    <div class="post-overlay">
                        <div>
                            <i class="bi bi-heart-fill"></i>

                            <strong>
                                {{ post.likesCount }}
                            </strong>
                        </div>

                        <div>
                            <i class="bi bi-chat-fill"></i>

                            <strong>
                                {{ post.commentsCount }}
                            </strong>
                        </div>
                    </div>

                </article>

            </div>

            <div
                v-else
                class="empty-posts"
            >
                <div class="empty-icon">
                    <i class="bi bi-camera"></i>
                </div>

                <h2>
                    Nenhuma publicação ainda
                </h2>

                <p v-if="isMyProfile">
                    Quando você publicar algo, aparecerá aqui.
                </p>
            </div>

        </section>
    </main>
    <UserConnectionsModal
        v-if="profileUser &&showConnections"
        :user="profileUser"
        :initial-tab="connectionsTab"
        @close="closeConnections"
    />

    <PostDetailModal
        v-if="selectedPost"
        :open="true"
        :post="selectedPost"
        :likes-count="selectedPost.likesCount"
        :comments-count="selectedPost.commentsCount"
        :is-liked="selectedPost.isLikedByMe"
        @close="closePost"
        @like="togglePostLike"
        @deleted="handlePostDeleted"
    />
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useAuthStore } from '@/stores/authStore';
import { useRoute } from 'vue-router';
import { useFollowStore } from '@/stores/followStore';
import FollowButton from '@/components/FollowButton.vue';
import UserConnectionsModal from '@/components/UserConectionsModal.vue';
import userService from '@/services/userService';
import PostDetailModal from '@/components/PostDetailModal.vue';
import postService from '@/services/postService';
import router from '@/router';

const followStore = useFollowStore();
const authStore = useAuthStore();
const route = useRoute();

const selectedPost = ref(null)

const profileUser = ref(null);
const posts = ref([]);

const showConnections = ref(false);
const connectionsTab = ref('followers');

const loadProfile = async () => {

    const response = await userService.getUserProfile(
        route.params.username
    );

    profileUser.value = response.data;

    followStore.setFollowingState(
        profileUser.value.id,
        profileUser.value.isFollowing
    );

    followStore.setFollowersCount(
        profileUser.value.id,
        profileUser.value.followersCount
    );

    if (isMyProfileRoute.value) {
        followStore.setMyFollowingCount(profileUser.value.followingCount);
    }
};

const loadPosts = async () => {
    const response = await userService.getUserPosts(
        route.params.username
    );

    posts.value = response.data;
}

const toggleFollow = async () => {

    const userId = profileUser.value.id

    await followStore.toggleFollow(userId)
}

const openFollowers = () => {
    connectionsTab.value = 'followers'
    showConnections.value = true
}

const openFollowing = () => {
    connectionsTab.value = 'following'
    showConnections.value = true
}

const closeConnections = () => {
    showConnections.value = false
}

const isMyProfile = computed(() => {
    return Number(profileUser.value?.id) === Number(authStore.user?.id);
});

const isMyProfileRoute = computed(() => {
    return !route.params.username
        || route.params.username === authStore.user?.username
})

const openPost = async (post) => {
    try {
        const response = await postService.getPost(post.id);
        
        selectedPost.value = response.data;
    } catch (error) {
        console.log(error);
    }
}

const closePost = () => {
    if (!selectedPost.value) return

    const gridPost = posts.value.find(
        post => post.id === selectedPost.value.id
    );

    if (gridPost) {
        gridPost.likesCount = selectedPost.value.likesCount;
        gridPost.commentsCount = selectedPost.value.commentsCount;
        gridPost.isLikedByMe = selectedPost.value.isLikedByMe;
    }

    selectedPost.value = null
}

const handlePostDeleted = (postId) => {
    posts.value = posts.value.filter(post => post.id !== postId);

    if (profileUser.value) {
        profileUser.value.postsCount = Math.max(
            0,
            (profileUser.value.postsCount ?? 1) - 1
        );
    }
};

const togglePostLike = async () => {
    if (!selectedPost.value) return;

    const post = selectedPost.value;

    const previousState = post.isLikedByMe;
    const previousCount = post.likesCount;

    post.isLikedByMe = !previousState;

    post.likesCount += post.isLikedByMe
        ? 1
        : -1;

    try {
        const response = await postService.toggleLike(
            post.id,
            previousState
        );

        post.isLikedByMe = response.data.isLikedByMe;
        post.likesCount = response.data.likesCount;

    } catch (error) {
        post.isLikedByMe = previousState;
        post.likesCount = previousCount;

        console.log(error);
    }
}

const editProfile = () => {
    router.push('/edit-profile');
}

const logout = async () => {
    await authStore.logout();
    router.push('/login');
}

onMounted(() => {
    loadProfile();
    loadPosts();
})

watch(
    () => route.params.username,
    () => {
        selectedPost.value = null;
        showConnections.value = false;
        loadProfile();
        loadPosts();
    }
);

</script>

<style scoped>

.profile-page {
    width: 100%;
    max-width: 935px;
    margin: 0 auto;
    padding: 30px 20px 60px;
    color: var(--text-primary);
}

.profile-header {
    display: flex;
    align-items: flex-start;
    gap: 80px;

    padding: 20px 40px 45px;
}

.profile-avatar {
    width: 150px;
    height: 150px;

    flex-shrink: 0;

    border-radius: 50%;

    overflow: hidden;

    display: flex;
    align-items: center;
    justify-content: center;
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-avatar .bi-person-circle {
    font-size: 150px;
    line-height: 1;
}

.profile-content {
    flex: 1;

    display: flex;
    flex-direction: column;
    gap: 20px;
}

.profile-identity {
    display: flex;
    align-items: center;
    gap: 16px;
}

.profile-identity h1 {
    margin: 0;

    font-size: 20px;
    font-weight: 400;
}

.profile-button {
    border: none;
    border-radius: 8px;

    padding: 8px 16px;

    font-weight: 600;

    cursor: pointer;
}

.profile-button:hover {
    opacity: .85;
}

.profile-stats {
    display: flex;
    align-items: center;

    gap: 40px;
}

.stat {
    display: flex;
    gap: 5px;
    padding: 0;
    background: none;
    border: none;
    font-size: 16px;
    color: var(--text-primary);
}

button.stat {
    cursor: pointer;
}

.stat strong {
    font-weight: 600;
}

.profile-description {
    display: flex;
    flex-direction: column;

    gap: 5px;
}

.profile-name {
    font-size: 14px;
}

.profile-bio {
    margin: 0;

    white-space: pre-line;

    font-size: 14px;
    line-height: 1.5;
}

.profile-tabs {
    border-top: 1px solid #dbdbdb;

    display: flex;
    justify-content: center;

    height: 53px;
}

.profile-tab {
    color: var(--text-primary);
    position: relative;
    height: 100%;
    display: flex;
    align-items: center;
    gap: 6px;
    border: none;
    background: none;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 1px;
    cursor: pointer;
}

.profile-tab.active::before {
    content: '';
    position: absolute;
    top: -1px;
    left: 0;
    right: 0;
    height: 1px;
    background: currentColor;
}

.posts-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 4px;
}

.profile-post {
    position: relative;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    cursor: pointer;
}

.profile-post > img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.multiple-images {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 2;
    color: white;
    font-size: 20px;
    text-shadow: 0 1px 3px rgba(0, 0, 0, .5);
}

.post-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 30px;
    opacity: 0;
    background: rgba(0, 0, 0, .35);
    color: white;
    transition: opacity .15s ease;
}

.profile-post:hover .post-overlay {
    opacity: 1;
}

.post-overlay div {
    display: flex;
    align-items: center;

    gap: 7px;

    font-size: 16px;
}

.empty-posts {
    min-height: 300px;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    text-align: center;
}

.empty-icon {
    width: 62px;
    height: 62px;

    border: 2px solid currentColor;
    border-radius: 50%;

    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-icon i {
    font-size: 30px;
}

.empty-posts h2 {
    margin: 15px 0 5px;
    font-size: 28px;
}

.empty-posts p {
    margin: 0;
    font-size: 14px;
}
</style>