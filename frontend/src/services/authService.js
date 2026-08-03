import api from "@/api/axios";

class AuthService {
    async login(credentials) {
        const response = await api.post('/login', credentials);
        return response.data       
    }
}

export default new AuthService();