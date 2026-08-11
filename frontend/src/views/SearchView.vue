<template>
    <main class="search-page">

        <header class="search-header">
            <h1>Pesquisar</h1>

            <div class="search-input-wrapper">
                <i class="bi bi-search"></i>

                <input
                    v-model="query"
                    type="text"
                    placeholder="Pesquisar por nome ou usuário"
                    class="search-input"
                    autofocus
                >

                <button
                    v-if="query"
                    class="clear-button"
                    type="button"
                    @click="clearQuery"
                >
                    <i class="bi bi-x-circle-fill"></i>
                </button>
            </div>
        </header>

        <section class="search-results">

            <p
                v-if="loading"
                class="status-message"
            >
                Buscando...
            </p>

            <p
                v-else-if="error"
                class="status-message error-message"
            >
                {{ error }}
            </p>

            <p
                v-else-if="!query.trim()"
                class="status-message"
            >
                Digite um nome ou usuário para começar a busca.
            </p>

            <p
                v-else-if="!results.length"
                class="status-message"
            >
                Nenhum usuário encontrado para "{{ query.trim() }}".
            </p>

            <div
                v-else
                class="results-list"
            >
                <div
                    v-for="user in results"
                    :key="user.id"
                    class="user-row"
                >
                    <UserHeader
                        :avatar-url="user.avatarUrl"
                        :username="user.username"
                        :name="user.name"
                        size="medium"
                    />

                    <div class="follow-button">
                        <FollowButton
                            v-if="user.id !== authStore.user?.id"
                            :is-following="followStore.isFollowing(user.id)"
                            :loading="followStore.isLoading(user.id)"
                            @toggle-follow="followStore.toggleFollow(user.id)"
                        />
                    </div>
                </div>
            </div>

        </section>
    </main>
</template>

<script setup>
import { ref, watch, onBeforeUnmount } from 'vue';
import UserHeader from '@/components/UserHeader.vue';
import FollowButton from '@/components/FollowButton.vue';
import userService from '@/services/userService';
import { useAuthStore } from '@/stores/authStore';
import { useFollowStore } from '@/stores/followStore';

const SEARCH_DEBOUNCE_MS = 350;

const authStore = useAuthStore();
const followStore = useFollowStore();

const query = ref('');
const results = ref([]);
const loading = ref(false);
const error = ref(null);

let debounceTimer = null;
let requestId = 0;

const search = async (term) => {
    const currentRequestId = ++requestId;

    loading.value = true;
    error.value = null;

    try {
        const users = await userService.searchUsers(term);

        if (currentRequestId !== requestId) {
            return;
        }

        results.value = users;

        users.forEach(user => {
            followStore.setFollowingState(user.id, user.isFollowing);
        });

    } catch (e) {
        if (currentRequestId !== requestId) {
            return;
        }

        results.value = [];
        error.value = 'Não foi possível buscar usuários.';

        console.log(e);

    } finally {
        if (currentRequestId === requestId) {
            loading.value = false;
        }
    }
};

const clearQuery = () => {
    query.value = '';
};

watch(query, (value) => {
    clearTimeout(debounceTimer);

    const term = value.trim();

    if (!term) {
        requestId++;
        loading.value = false;
        error.value = null;
        results.value = [];
        return;
    }

    debounceTimer = setTimeout(() => {
        search(term);
    }, SEARCH_DEBOUNCE_MS);
});

onBeforeUnmount(() => {
    clearTimeout(debounceTimer);
});
</script>

<style scoped>
.search-page {
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
    padding: 50px 20px 60px;
    color: var(--text-primary);
}

.search-header {
    margin-top: 80px;
}

.search-header h1 {
    margin: 0 0 20px;
    font-size: 22px;
    font-weight: 600;
}

.search-input-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;

    padding: 10px 16px;

    background-color: var(--bg-semi-transparent);
    border: 1px solid #333;
    border-radius: 15px;
}

.search-input-wrapper:focus-within {
    border-color: white;
}

.search-input-wrapper i.bi-search {
    color: var(--text-tertiary);
    font-size: 1rem;
}

.search-input {
    flex: 1;

    border: none;
    outline: none;
    background: transparent;

    color: var(--text-primary);
    font-size: 0.95rem;
}

.search-input::placeholder {
    color: var(--text-tertiary);
}

.clear-button {
    display: flex;
    align-items: center;

    background: none;
    border: none;
    padding: 0;

    color: var(--text-tertiary);
    cursor: pointer;
}

.clear-button:hover {
    color: var(--text-primary);
}

.search-results {
    margin-top: 24px;
}

.status-message {
    color: var(--text-tertiary);
    text-align: center;
    margin-top: 40px;
}

.error-message {
    color: #f66;
}

.results-list {
    display: flex;
    flex-direction: column;
}

.user-row {
    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 10px 4px;

    border-bottom: 1px solid var(--bg-dark-gray);
}
</style>
