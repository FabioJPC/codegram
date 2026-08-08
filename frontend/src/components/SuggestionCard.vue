<template>
    <div class="suggestion-card">
        <div class="suggestion-container">

            <img 
                v-if="user.avatarUrl"
                :src="user.avatarUrl"
                :alt="user.username"
                class="avatar-img"
            />

            <i
                v-else
                class="bi bi-person-circle"
            ></i>

            <div class="user-info">
                <strong>{{ user.username }}</strong>
                <span>{{ user.name }}</span>
            </div>

        </div>

        <button class="follow-button">
            Seguir
        </button>
    </div>
</template>

<script setup>
defineProps({
    user: {
        type: Object,
        required: true
    }
});
</script>