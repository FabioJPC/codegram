<template>
    <div class="suggestion-card">
        <div class="suggestion-container">
            <UserHeader
                :avatar-url="user.avatarUrl" 
                :username="user.username"
                size="large"
            />
        </div>

        <button class="follow-button">
            Seguir
        </button>
    
    </div>
</template>

<script setup>
import UserHeader from './UserHeader.vue';

defineProps({
    user: {
        type: Object,
        required: true
    }
});
</script>

<style scoped>
.suggestion-card {
    padding: 0 4px;
    display: flex;
    width: 100%;
    justify-content: space-between;
}

.suggestion-card:hover {
    box-shadow: 0px 0px 8px;
}

.suggestion-container {
    display: flex;
    width: 100;  
    color: var(--text-primary);
}

.follow-button {
    background-color: transparent;
    border: none;
    color: var(--text-primary);
    font-weight: 200;
    font-size: 0.7rem;
}
</style>