<template>
    <button @click="emit('toggle-follow')">
        {{ isFollowing ? 'Seguindo' : 'Seguir' }}
    </button>
</template>

<script setup>

const props = defineProps({
    isFollowing: {
        type: Boolean,
        required: true
    }
})

const emit = defineEmits(['toogle-follow'])

</script>