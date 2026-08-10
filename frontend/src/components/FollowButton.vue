<template>
    <button
        class="follow-button"
        :class="{ following: isFollowing }"
        :disabled="loading"
        @click="emit('toggle-follow')"
    >
        {{
            loading
                ? '...'
                : isFollowing
                    ? 'Seguindo'
                    : 'Seguir'
        }}
    </button>
</template>

<script setup>
defineProps({
    isFollowing: {
        type: Boolean,
        required: true
    },

    loading: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits([
    'toggle-follow'
])
</script>

<style scoped>
.follow-button {
    min-width: 90px;

    padding: 8px 16px;

    border: none;
    border-radius: 8px;

    background: var(--button-blue);
    color: var(--text-primary);

    font-size: 14px;
    font-weight: 600;

    cursor: pointer;
}

.follow-button:hover {
    opacity: .9;
}

.follow-button.following {
    background: var(--background-secondary);
    color: var(--text-primary);
}

.follow-button:disabled {
    opacity: .6;
    cursor: default;
}
</style>