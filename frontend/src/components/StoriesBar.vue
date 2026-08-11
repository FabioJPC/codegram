<template>
    <div class="stories-bar">
        <div class="story-avatar own">
            <button
                type="button"
                class="ring-button"
                :class="{ seen: ownStory?.seen, empty: !ownStory }"
                @click="handleOwnClick"
            >
                <span class="ring">
                    <span class="ring-gap">
                        <img
                            v-if="authStore.user?.avatarUrl"
                            :src="authStore.user.avatarUrl"
                            :alt="authStore.user?.username"
                        />
                        <i v-else class="bi bi-person-circle"></i>
                    </span>
                </span>

                <span class="add-badge" @click.stop="openCreateModal">
                    <i class="bi bi-plus-circle-fill"></i>
                </span>
            </button>

            <span class="username">Seu story</span>
        </div>

        <button
            v-for="story in otherStories"
            :key="story.id"
            type="button"
            class="story-avatar"
            :class="{ seen: story.seen }"
            @click="openStory(story.id)"
        >
            <span class="ring">
                <span class="ring-gap">
                    <img v-if="story.avatarUrl" :src="story.avatarUrl" :alt="story.username" />
                    <i v-else class="bi bi-person-circle"></i>
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

        <CreateStoryModal
            :open="showCreateModal"
            @close="showCreateModal = false"
        />
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useStoryStore } from '@/stores/storyStore';
import { useAuthStore } from '@/stores/authStore';
import StoryViewer from './StoryViewer.vue';
import CreateStoryModal from './CreateStoryModal.vue';

const storyStore = useStoryStore();
const authStore = useAuthStore();

const activeStoryId = ref(null);
const storyIdsSnapshot = ref([]);
const showCreateModal = ref(false);

const ownStory = computed(() =>
    storyStore.stories.find((story) => story.id === authStore.user?.id)
);

const otherStories = computed(() =>
    storyStore.stories.filter((story) => story.id !== authStore.user?.id)
);

const openStory = (id) => {
    storyIdsSnapshot.value = storyStore.stories.map((story) => story.id);
    activeStoryId.value = id;
};

const closeStory = () => {
    activeStoryId.value = null;
};

const openCreateModal = () => {
    showCreateModal.value = true;
};

const handleOwnClick = () => {
    if (ownStory.value) {
        openStory(ownStory.value.id);
    } else {
        openCreateModal();
    }
};
</script>

<style scoped>
.stories-bar {
    width: 100%;
    display: flex;
    gap: 40px;
    justify-content: center;
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
    width: 100px;
    height: 100px;
    border-radius: 50%;
    padding: 2.5px;
    background: linear-gradient(45deg, #f9ce34 0%, #ee2a7b 50%, #6228d7 100%);
    display: flex;
    flex-shrink: 0;
}

.story-avatar.seen .ring {
    background: var(--text-tertiary);
}

.story-avatar.own {
    background: none;
    border: none;
    padding: 0;
}

.ring-button {
    position: relative;
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    display: flex;
}

.ring-button.seen .ring {
    background: var(--text-tertiary);
}

.ring-button.empty .ring {
    background: var(--bg-dark-gray);
}

.add-badge {
    position: absolute;
    right: 2px;
    bottom: 2px;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: var(--bg-main);

    display: flex;
    align-items: center;
    justify-content: center;

    color: var(--button-blue);
    font-size: 1.35rem;
    line-height: 1;
    cursor: pointer;
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

.ring-gap i {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: var(--bg-dark-gray);
    color: var(--text-tertiary);
    font-size: 2.6rem;
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
