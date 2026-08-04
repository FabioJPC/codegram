import api from "@/api/axios";
import { ref } from "vue";

const TOKEN_KEY = 'api-token';
const isAuthenticatedState = ref(false);

if (localStorage.getItem(TOKEN_KEY)) {
    isAuthenticatedState.value = true;
}

export default {
    async login(credentials) {
        const response = await api.post('/login', credentials);
        return response.data;    
    },

    async register(data) {
        const response = await api.post('/register', data);
        return response.data;
    },

    setToken(token) {
        localStorage.setItem(TOKEN_KEY, token);
        isAuthenticatedState.value = true;
    },

    getToken() {
        return localStorage.getItem(TOKEN_KEY);
    },

    isAuthenticated() {
        return isAuthenticatedState.value || !!this.getToken();
    },

    logout() {
        localStorage.removeItem('token');
        isAuthenticatedState.value = false
    }
}
