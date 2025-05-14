import { defineStore } from 'pinia';
import axios from 'axios';
import { ref } from 'vue';

// Đảm bảo baseURL trỏ đến server Laravel
const baseURL = import.meta.env.VITE_APP_URL || 'http://localhost:8000';

// Cấu hình chung cho tất cả instance axios
const axiosConfig = {
    withCredentials: true,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
};

// Instance cho sanctum
const sanctumAxios = axios.create({
    ...axiosConfig,
    baseURL: baseURL,
    withCredentials: true
});

// Instance cho API
const apiAxios = axios.create({
    ...axiosConfig,
    baseURL: `${baseURL}/api`,
    withCredentials: true
});

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null);
    const loading = ref(false);
    const error = ref(null);
    const token = ref(localStorage.getItem('token'));
    const isAuthenticated = ref(false);

    const login = async (email, password) => {
        loading.value = true;
        error.value = null;
        
        try {
            // Get CSRF cookie
            console.log('Getting CSRF cookie...');
            await sanctumAxios.get('/sanctum/csrf-cookie', {
                withCredentials: true
            });
            
            console.log('Sending login request...');
            // Login request
            const response = await apiAxios.post('/login', {
                email,
                password
            }, {
                withCredentials: true
            });
            
            console.log('Login response:', response.data);
            
            const { token: newToken, user: newUser } = response.data;
            
            user.value = newUser;
            token.value = newToken;
            isAuthenticated.value = true;
            
            localStorage.setItem('token', newToken);
            apiAxios.defaults.headers.common['Authorization'] = `Bearer ${newToken}`;
            
            return response;
        } catch (err) {
            console.error('Login error:', err.response || err);
            error.value = err.response?.data?.message || 'Đăng nhập thất bại';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const logout = async () => {
        try {
            await apiAxios.post('/logout');
        } catch (err) {
            console.error('Logout error:', err);
        } finally {
            user.value = null;
            token.value = null;
            isAuthenticated.value = false;
            localStorage.removeItem('token');
            delete apiAxios.defaults.headers.common['Authorization'];
        }
    };

    const checkAuth = async () => {
        if (!token.value) return;
        
        try {
            const response = await apiAxios.get('/user');
            user.value = response.data;
            isAuthenticated.value = true;
        } catch (err) {
            token.value = null;
            user.value = null;
            isAuthenticated.value = false;
            localStorage.removeItem('token');
        }
    };

    return {
        user,
        loading,
        error,
        token,
        isAuthenticated,
        login,
        logout,
        checkAuth
    };
}); 