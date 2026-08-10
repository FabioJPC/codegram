<template>
    <div
        class="connections-overlay"
        @click.self="close"
    >
        <section class="connections-modal">
            <header class="connections-header">
                <button
                    class="button"
                    @click="close"
                >
                    <i class="bi bi-x-lg"></i>
                </button>
            </header>

            <nav class="connections-tabs">
                <button
                    class="button"
                    :class="{ active: activeTab === 'followers' }"
                    @click="changeTab('followers')"
                >
                    Seguidores
                </button>

                <button
                    class="button"
                    :class="{ active: activeTab === 'following' }"
                    @click="changeTab('following')"
                >
                    Seguindo
                </button>
            </nav>

            <div class="connections-body">
                <p v-if="loading">
                    Carregando...
                </p>

                <p
                    v-else-if="error"
                    class="error-message"
                >
                    {{ error }}
                </p>

                <div
                    v-else-if="currentUsers.length"
                    class="users-list"
                >
                    <div
                        v-for="connectionUser in currentUsers"
                        :key="connectionUser.id"
                        class="user-row"
                    >
                        <UserHeader
                            :avatar-url="connectionUser.avatarUrl"
                            :username="connectionUser.username"
                            :name="connectionUser.name"
                            size="medium"
                        />

                        <div class="follow-button">
                            <FollowButton
                                v-if="connectionUser.id !== authStore.user?.id"
                                :is-following="followStore.isFollowing(connectionUser.id)"
                                :loading="followStore.isLoading(connectionUser.id)"
                                @toggle-follow="followStore.toggleFollow(connectionUser.id)"
                            />
                        </div>
                    </div>
                </div>

                <p v-else>
                    {{
                        activeTab === 'followers'
                            ? 'Nenhum seguidor.'
                            : 'Não está seguindo ninguém.'
                    }}
                </p>
            </div>

        </section>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import UserHeader from './UserHeader.vue';
import userService from '@/services/userService';
import FollowButton from '@/components/FollowButton.vue';
import { useAuthStore } from '@/stores/authStore.js';
import { useFollowStore } from '@/stores/followStore.js';

const authStore = useAuthStore();
const followStore = useFollowStore();

const emit = defineEmits(['close']);

const props = defineProps({
    user: {
        type: Object,
        required: true
    },

    initialTab: {
        type: String,
        default: 'followers'
    }
});

const activeTab = ref(props.initialTab);

const followers = ref([]);
const following = ref([]);

const followersLoaded = ref(false);
const followingLoaded = ref(false);

const loading = ref(false);
const error = ref(null);

const currentUsers = computed(() => {
    return activeTab.value === 'followers'
        ? followers.value
        : following.value
});

const close = () => {
    emit('close')
};

const changeTab = async (tab) => {
    activeTab.value = tab

    await loadCurrentTab()
};

const loadCurrentTab = async () => {
    if (!props.user?.id) {
        return;
    }

    if (
        activeTab.value === 'followers'
        && followersLoaded.value
    ) {
        return
    }

    if (
        activeTab.value === 'following'
        && followingLoaded.value
    ) {
        return
    }

    loading.value = true;
    error.value = null;

    try {

        if (activeTab.value === 'followers') {
            const response = await userService.getFollowers(
                props.user.id
            );

            followers.value = response.data
            followersLoaded.value = true
            followers.value.forEach(user => {
                followStore.setFollowingState(
                    user.id,
                    user.isFollowing
                )
            })

        } else {
            const response = await userService.getFollowing(
                props.user.id
            );

            following.value = response.data;
            followingLoaded.value = true;
            following.value.forEach(user => {
                followStore.setFollowingState(
                    user.id,
                    user.isFollowing
                )
            })
        }

    } catch (e) {
        console.log(e);

        error.value = 'Não foi possível carregar os usuários.';

    } finally {
        loading.value = false;
    }
}

watch(
    () => props.initialTab,
    (tab) => {
        activeTab.value = tab;
    }
)

onMounted(() => {
    loadCurrentTab()
})
</script>

<style scoped>
.connections-overlay {
    position: fixed;
    inset: 0;

    z-index: 1000;

    display: flex;
    align-items: center;
    justify-content: center;

    background: rgba(0, 0, 0, 0.65);
}


.connections-modal {
    width: 450px;
    height: 600px;

    display: flex;
    flex-direction: column;

    background: var(--bg-modal);
    color: var(--text-primary, #111);

    border-radius: 12px;

    overflow: hidden;

    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
}

.connections-header {
    display: flex;
    justify-content: flex-end;
    padding-right: 10px;
    padding-top: 10px;
}

.button {
    background-color: transparent;
    color: var(--text-primary);
    border: none;
}

.follow-button {
    height: 70%;
}

.active {
    border-bottom: 2px solid var(--text-tertiary);
}

.connections-tabs {
    display: flex;
    justify-content: space-around;
}

.connections-body {
    margin-top: 20px;
    padding: 10px;
}

.user-row {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid var(--bg-dark-gray);
    align-items: center;
}

</style>