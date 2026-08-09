<template>
    <nav class="nav">
        <div class="logo">
            <Logo/>
        </div>
        <div class="buttons">
            <router-link 
                :to="`/feed`"
                class="nav-link"
            >
                <i class="bi bi-house-door-fill"></i>
                <span class="nav-label">Página Inicial</span>
            </router-link>
            
            <div class="nav-link">
                <i class="bi bi-search"></i>
                <span class="nav-label">Pesquisar</span>
            </div>

            <div class="nav-link" @click="showModal = true">
                <i class="bi bi-plus-circle"></i>
                <span class="nav-label">Criar</span>
            </div>

            <router-link 
                :to="`/profile/${authStore.user?.username}`" 
                class="nav-link profile-link"
            >
                <img 
                    v-if="authStore.user?.avatarUrl" 
                    :src="authStore.user.avatarUrl" 
                    :alt="authStore.user?.username"
                    class="profile-avatar"
                />

                <i v-else class="bi bi-person-circle"></i>

                <span class="nav-label">Perfil</span>
            </router-link>
        </div>
    </nav>

    <CreatePostModal
        :open="showModal"
        @close="showModal = false"
    />
</template>

<script setup>
import CreatePostModal from './CreatePostModal.vue';
import { useAuthStore } from '@/stores/authStore';
import Logo from '@/components/Logo.vue';
import { ref } from 'vue';

const authStore = useAuthStore();

const showModal = ref(false);
</script>

<style scoped>
.nav {
    position: sticky;
    padding: 20px;
    flex: 2 1 0%;
    display: flex;
    flex-direction: column;
    gap: 70px;
    height: 100vh;
    top: 0;
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

.nav-link {
    font-size: 1.6rem;
    color: var(--text-primary);
    cursor: pointer;
    width: 24px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.3s ease;
    position: relative;
}

.nav-link:hover {
    opacity: 0.7;
}

.nav-label {
    opacity: 0;
    font-size: 1rem;
    font-weight: 500;
    white-space: nowrap;
    transform: translateX(-20px);
    transition: all 0.2s ease;
    color: var(--text-primary);
}

.nav-link:hover .nav-label {
    opacity: 1;
    transform: translateX(0);
}

.profile-link {
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    text-decoration: none;
    transition: opacity 0.2s;
}

.profile-link:hover {
    opacity: 0.8;
}

.profile-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    object-fit: cover;
    margin-left: 80px;
}

.nav-link i {
    font-size: 1.6rem;
    color: var(--text-primary);
}
</style>