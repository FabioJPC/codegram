import api from "@/api/axios";

const TOKEN_KEY = 'api-token';

export default {
    async login(credentials) {
        const response = await api.post('/login', credentials);
        return response.data.data;    
    },

    async register(data) {
        const response = await api.post('/register', data);
        return response.data.data;
    },

    async logout() {
        try {
            await api.post('/logout');
        }
        finally {
            localStorage.removeItem(TOKEN_KEY);
        }
    },

    async getMe() {
        const response = await api.get('/me');
        return response.data.data;
    },

    setToken(token) {
        localStorage.setItem(TOKEN_KEY, token);
    },

    getToken() {
        return localStorage.getItem(TOKEN_KEY);
    },
}
