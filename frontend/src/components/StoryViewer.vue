<template>
    <div class="story-viewer-overlay" @click.self="close">
        <button type="button" class="close-button" @click="close">
            <i class="bi bi-x-lg"></i>
        </button>

        <button
            v-if="hasPrevUser"
            type="button"
            class="nav-button prev"
            @click="goToPrevUser"
        >
            <i class="bi bi-chevron-left"></i>
        </button>

        <button
            v-if="hasNextUser"
            type="button"
            class="nav-button next"
            @click="goToNextUser"
        >
            <i class="bi bi-chevron-right"></i>
        </button>

        <div v-if="currentUser" class="story-card">
            <div class="progress-bars">
                <div
                    v-for="(item, index) in currentUser.items"
                    :key="item.id"
                    class="progress-track"
                >
                    <div class="progress-fill" :style="progressStyle(index)"></div>
                </div>
            </div>

            <UserHeader
                class="story-user"
                :avatar-url="currentUser.avatarUrl"
                :username="currentUser.username"
                :clickable="false"
                size="small"
            />

            <div
                class="story-media"
                @pointerdown="pause"
                @pointerup="resume"
                @pointerleave="resume"
            >
                <img :src="currentItem?.mediaUrl" :alt="currentUser.username" />

                <div class="tap-zone left" @click="prevItem"></div>
                <div class="tap-zone right" @click="nextItem"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { useStoryStore } from '@/stores/storyStore';
import UserHeader from './UserHeader.vue';

const props = defineProps({
    // Ordem fixa (snapshot) dos ids de usuário a navegar. Fica congelada
    // pelo StoriesBar para não pular quando a fila é reordenada.
    storyIds: {
        type: Array,
        required: true,
    },
    startId: {
        type: [Number, String],
        required: true,
    },
});

const emit = defineEmits(['close']);

const storyStore = useStoryStore();

const TICK_MS = 50;
const DEFAULT_DURATION = 5000;

const userIndex = ref(Math.max(props.storyIds.indexOf(props.startId), 0));
const currentItemIndex = ref(0);
const progress = ref(0);
const paused = ref(false);

let timer = null;

const currentUser = computed(() =>
    storyStore.stories.find((story) => story.id === props.storyIds[userIndex.value])
);

const currentItem = computed(() => currentUser.value?.items[currentItemIndex.value] ?? null);

const hasPrevUser = computed(() => userIndex.value > 0);
const hasNextUser = computed(() => userIndex.value < props.storyIds.length - 1);

const progressStyle = (index) => {
    if (index < currentItemIndex.value) {
        return { width: '100%' };
    }

    if (index > currentItemIndex.value) {
        return { width: '0%' };
    }

    return { width: `${progress.value}%` };
};

const clearTimer = () => {
    if (timer) {
        clearInterval(timer);
        timer = null;
    }
};

const startTimer = () => {
    clearTimer();
    progress.value = 0;

    const duration = currentItem.value?.duration ?? DEFAULT_DURATION;

    timer = setInterval(() => {
        if (paused.value) {
            return;
        }

        progress.value += (TICK_MS / duration) * 100;

        if (progress.value >= 100) {
            nextItem();
        }
    }, TICK_MS);
};

const pause = () => {
    paused.value = true;
};

const resume = () => {
    paused.value = false;
};

const nextItem = () => {
    if (!currentUser.value) {
        return;
    }

    if (currentItemIndex.value < currentUser.value.items.length - 1) {
        currentItemIndex.value++;
        startTimer();
    } else {
        goToNextUser();
    }
};

const prevItem = () => {
    if (currentItemIndex.value > 0) {
        currentItemIndex.value--;
        startTimer();
    } else {
        goToPrevUser();
    }
};

const goToNextUser = () => {
    if (hasNextUser.value) {
        userIndex.value++;
        currentItemIndex.value = 0;
        startTimer();
    } else {
        close();
    }
};

const goToPrevUser = () => {
    if (hasPrevUser.value) {
        userIndex.value--;
        currentItemIndex.value = 0;
        startTimer();
    }
};

const close = () => {
    clearTimer();
    emit('close');
};

const handleKeydown = (event) => {
    if (event.key === 'Escape') close();
    if (event.key === 'ArrowRight') nextItem();
    if (event.key === 'ArrowLeft') prevItem();
};

// Assim que o story de um usuário é exibido pro viewer, ele é marcado como
// visto e vai pro final da fila (mesma regra vale ao abrir o primeiro).
watch(
    currentUser,
    (user) => {
        if (user) {
            storyStore.markAsSeen(user.id);
        }
    },
    { immediate: true }
);

onMounted(() => {
    startTimer();
    window.addEventListener('keydown', handleKeydown);
});

onBeforeUnmount(() => {
    clearTimer();
    window.removeEventListener('keydown', handleKeydown);
});
</script>

<style scoped>
.story-viewer-overlay {
    position: fixed;
    inset: 0;
    z-index: 1000;

    background: rgba(0, 0, 0, 0.85);

    display: flex;
    align-items: center;
    justify-content: center;
}

.story-card {
    position: relative;
    width: min(420px, 90vw);
    height: min(90vh, 760px);

    background: #000;
    border-radius: 12px;
    overflow: hidden;

    display: flex;
    flex-direction: column;
}

.progress-bars {
    position: absolute;
    top: 8px;
    left: 8px;
    right: 8px;
    z-index: 2;

    display: flex;
    gap: 4px;
}

.progress-track {
    flex: 1;
    height: 3px;
    border-radius: 2px;
    background: rgba(255, 255, 255, 0.35);
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: #fff;
    transition: width 0.05s linear;
}

.story-user {
    position: absolute;
    top: 20px;
    left: 12px;
    z-index: 2;
    color: #fff;
}

.story-media {
    position: relative;
    flex: 1;
}

.story-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.tap-zone {
    position: absolute;
    top: 0;
    bottom: 0;
    width: 35%;
    cursor: pointer;
}

.tap-zone.left {
    left: 0;
}

.tap-zone.right {
    right: 0;
}

.close-button {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 3;

    border: none;
    background: none;

    color: #fff;
    font-size: 24px;

    cursor: pointer;
}

.nav-button {
    position: fixed;
    top: 50%;
    transform: translateY(-50%);
    z-index: 3;

    width: 40px;
    height: 40px;
    border: none;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);

    color: #fff;
    font-size: 20px;

    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.nav-button.prev {
    left: 30px;
}

.nav-button.next {
    right: 30px;
}
</style>
