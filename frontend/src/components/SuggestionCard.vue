<template>
    <div class="suggestion-card">
        <div class="suggestion-container">
            <UserHeader
                :avatar-url="user.avatarUrl" 
                :username="user.username"
                size="large"
            />
        </div>


        <span 
            class="profile-link"
            @click="goToProfile"
        >
            Ver perfil
        </span>
    
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import UserHeader from './UserHeader.vue';
import { defineProps } from 'vue';

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
});

const router = useRouter();

const goToProfile = () => {
    router.push(`/profile/${props.user.username}`);
}
</script>

<style scoped>
.suggestion-card {
    padding: 0 4px;
    display: flex;
    width: 100%;
    justify-content: space-between;
}

.suggestion-card:hover {
    box-shadow: 0px 10px 15px -10px;
}

.suggestion-container {
    display: flex;
    width: 100;  
    color: var(--text-primary);
}

.profile-link {
    color: var(--text-primary);
    font-weight: 200;
    font-size: 0.7rem;
    display: flex;
    align-items: center;
    cursor: pointer;
}
</style>