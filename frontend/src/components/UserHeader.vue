<template>
    <div 
        class="user-header" 
        :class="{ [size]: true , clickable: clickable}"
        @click="handleClick"
    >
        <img 
            v-if="avatarUrl" 
            :src="avatarUrl" 
            :alt="username"
            class="avatar"
        />
        <i v-else class="bi bi-person-circle"></i>
        
        <div class="user-info">
            <strong class="username">{{ username }}</strong>
            <span v-if="name" class="name">{{ name }}</span>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

const props = defineProps({
    avatarUrl: {
        type: String,
        default: ''
    },
    username: {
        type: String,
        required: true
    },
    name: {
        type: String,
        default: ''
    },
    size: {
        type: String,
        default: 'medium',
        validator: (value) => ['small', 'medium', 'large'].includes(value)
    },
    clickable: {
        type: Boolean,
        default: true
    },
});

const emit = defineEmits(['click']);

const handleClick = () => {
    if (! props.clickable) return;

    emit('click');

    router.push(`/profile/${props.username}`);
};

</script>

<style scoped>
.user-header {
    display: flex;
    align-items: center;
    gap: 10px;
}

.avatar,
i {
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.small .avatar,
.small i {
    width: 24px;
    height: 24px;
}

.medium .avatar,
.medium i {
    width: 32px;
    height: 32px;
}

.large .avatar,
.large i {
    width: 48px;
    height: 48px;
}

.user-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.username {
    color: inherit;
    font-weight: 600;
    font-size: 0.9rem;
}

.name {
    font-size: 0.8rem;
    color: inherit;
}

i {
    font-size: 1.5rem;
    color: inherit;
}

.user-header.clickable {
    cursor: pointer;
    border-radius: 8px;
    padding: 8px;
    transition: background-color 0.2s;
}
</style>