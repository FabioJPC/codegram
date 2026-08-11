import { defineStore } from 'pinia';
import storyService from '@/services/storyService';

export const useStoryStore = defineStore('story', {
    state: () => ({
        stories: [],
        error: null,
    }),

    actions: {
        async loadStories() {
            this.error = null;

            try {
                const stories = await storyService.getFeed();

                this.stories = this.groupByAuthor(stories);

            } catch (e) {
                this.error = 'Não foi possível carregar os stories.';
                console.log(e);
            }
        },

        groupByAuthor(stories) {
            const groups = [];
            const groupsByAuthorId = new Map();

            for (const story of stories) {
                const author = story.author;

                let group = groupsByAuthorId.get(author.id);

                if (!group) {
                    group = {
                        id: author.id,
                        username: author.username,
                        avatarUrl: author.avatarUrl,
                        seen: false,
                        items: [],
                    };

                    groupsByAuthorId.set(author.id, group);
                    groups.push(group);
                }

                group.items.push({
                    id: story.id,
                    mediaUrl: story.url,
                });
            }

            return groups;
        },

        markAsSeen(userId) {
            const index = this.stories.findIndex((story) => story.id === userId);

            if (index === -1) {
                return;
            }

            const [story] = this.stories.splice(index, 1);

            story.seen = true;

            this.stories.push(story);
        },
    },
});
