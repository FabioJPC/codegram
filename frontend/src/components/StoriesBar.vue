<template>
    <div class="stories-bar">
        <button
            v-for="story in storyStore.stories"
            :key="story.id"
            type="button"
            class="story-avatar"
            :class="{ seen: story.seen }"
            @click="openStory(story.id)"
        >
            <span class="ring">
                <span class="ring-gap">
                    <img :src="story.avatarUrl" :alt="story.username" />
                </span>
            </span>

            <span class="username">{{ story.username }}</span>
        </button>

        <StoryViewer
            v-if="activeStoryId !== null"
            :story-ids="storyIdsSnapshot"
            :start-id="activeStoryId"
            @close="closeStory"
        />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useStoryStore } from '@/stores/storyStore';
import StoryViewer from './StoryViewer.vue';

const storyStore = useStoryStore();

const activeStoryId = ref(null);
const storyIdsSnapshot = ref([]);

const openStory = (id) => {
    // Congela a ordem atual da fila: o viewer navega por essa lista fixa
    // para não pular usuários quando um story vai pro final da fila.
    storyIdsSnapshot.value = storyStore.stories.map((story) => story.id);
    activeStoryId.value = id;
};

const closeStory = () => {
    activeStoryId.value = null;
};
</script>

<style scoped>
.stories-bar {
    display: flex;
    gap: 18px;
    align-items: flex-start;
    padding: 6px 4px;
    overflow-x: auto;
    scrollbar-width: none;
}

.stories-bar::-webkit-scrollbar {
    display: none;
}

.story-avatar {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    width: 74px;
    flex-shrink: 0;
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
}

.ring {
    width: 66px;
    height: 66px;
    border-radius: 50%;
    padding: 2.5px;
    background: linear-gradient(45deg, #f9ce34 0%, #ee2a7b 50%, #6228d7 100%);
    display: flex;
    flex-shrink: 0;
}

.story-avatar.seen .ring {
    background: var(--text-tertiary);
}

.ring-gap {
    flex: 1;
    border-radius: 50%;
    padding: 2.5px;
    background: var(--bg-main);
    display: flex;
}

.ring-gap img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.username {
    font-size: 0.7rem;
    color: var(--text-primary);
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>
