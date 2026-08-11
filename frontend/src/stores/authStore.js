import authService from "@/services/authService";
import { defineStore } from "pinia";
import { useFeedStore } from "@/stores/feedStore";
import { useStoryStore } from "@/stores/storyStore";
import { useSuggestionStore } from "@/stores/suggestionStore";

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        initialized: false,
    }),

    getters: {
        isAuthenticated: (state) => state.user !== null,
    },

    actions: {
        setUser(user) {
            this.user = user;
        },

        clearUser() {
            this.user = null;
        },

        async loadUser() {
            try {
                const user = await authService.getMe();
                this.user = user;

            } catch (error) {
                this.user = null;

                console.log(error);
            } finally {
                this.initialized = true;
            }
        },

        async login(credentials) {
            try {
                const response = await authService.login(credentials);

                authService.setToken(response.token);

                await this.loadUser();
            } catch (error) {
                throw error;
            }
        },

        async register(data) {
            const response = await authService.register(data);

            authService.setToken(response.token);

            await this.loadUser();
        },

        async logout() {
            try {
                authService.logout();
            }
            finally {
                this.clearUser();
                this.clearOtherStores();
            }
        },

        clearOtherStores() {
            // Pinia stores are singletons that persist for the whole SPA
            // session, so leftover data from the previous account (posts,
            // stories, suggestions) must be wiped here or it stays visible
            // to the next user who logs in on the same tab.
            useFeedStore().$reset();
            useStoryStore().$reset();
            useSuggestionStore().$reset();
        },
    },
})