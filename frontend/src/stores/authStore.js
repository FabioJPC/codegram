import authService from "@/services/authService";
import { defineStore } from "pinia";

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

            authService.setToken(response.data.token);

            await this.loadUser();
        },

        async logout() {
            try {
                authService.logout();
            }
            finally {
                this.clearUser();
            }
        },
    },
})