import { defineStore } from 'pinia';

// Dados mockados apenas para o frontend — o backend ainda não tem uma
// feature de stories. Quando existir, essa carga deve vir de um storyService.
const MOCK_STORIES = [
    {
        id: 1,
        username: 'ana.dev',
        avatarUrl: 'https://i.pravatar.cc/150?img=5',
        seen: false,
        items: [
            { id: 11, mediaUrl: 'https://picsum.photos/seed/story-1a/720/1280', duration: 5000 },
            { id: 12, mediaUrl: 'https://picsum.photos/seed/story-1b/720/1280', duration: 5000 },
        ],
    },
    {
        id: 2,
        username: 'joao.silva',
        avatarUrl: 'https://i.pravatar.cc/150?img=12',
        seen: false,
        items: [
            { id: 21, mediaUrl: 'https://picsum.photos/seed/story-2a/720/1280', duration: 5000 },
        ],
    },
    {
        id: 3,
        username: 'marina.costa',
        avatarUrl: 'https://i.pravatar.cc/150?img=32',
        seen: false,
        items: [
            { id: 31, mediaUrl: 'https://picsum.photos/seed/story-3a/720/1280', duration: 4000 },
            { id: 32, mediaUrl: 'https://picsum.photos/seed/story-3b/720/1280', duration: 4000 },
            { id: 33, mediaUrl: 'https://picsum.photos/seed/story-3c/720/1280', duration: 4000 },
        ],
    },
    {
        id: 4,
        username: 'pedro.lima',
        avatarUrl: 'https://i.pravatar.cc/150?img=15',
        seen: false,
        items: [
            { id: 41, mediaUrl: 'https://picsum.photos/seed/story-4a/720/1280', duration: 5000 },
        ],
    },
    {
        id: 5,
        username: 'carla.souza',
        avatarUrl: 'https://i.pravatar.cc/150?img=45',
        seen: false,
        items: [
            { id: 51, mediaUrl: 'https://picsum.photos/seed/story-5a/720/1280', duration: 5000 },
            { id: 52, mediaUrl: 'https://picsum.photos/seed/story-5b/720/1280', duration: 5000 },
        ],
    },
    {
        id: 6,
        username: 'bruno.rocha',
        avatarUrl: 'https://i.pravatar.cc/150?img=51',
        seen: false,
        items: [
            { id: 61, mediaUrl: 'https://picsum.photos/seed/story-6a/720/1280', duration: 5000 },
        ],
    },
];

export const useStoryStore = defineStore('story', {
    state: () => ({
        stories: MOCK_STORIES.map((story) => ({ ...story, items: [...story.items] })),
    }),

    actions: {
        // Marca os stories de um usuário como vistos e manda ele pro final
        // da fila, igual ao comportamento do Instagram.
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
