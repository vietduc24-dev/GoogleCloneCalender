import { defineStore } from 'pinia';
import { ref } from 'vue';
import { sanctumApi, api } from '../utils/axios';

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null);
    const loading = ref(false);
    const error = ref(null);
    const token = ref(localStorage.getItem('token'));
    const isAuthenticated = ref(false);

    const register = async (userData) => {
        loading.value = true;
        error.value = null;
        
        try {
            // Get CSRF cookie
            await sanctumApi.get('/sanctum/csrf-cookie');
            
            // Register request
            const response = await api.post('/register', userData);
            
            if (response.data.status === 'success') {
                const { token: newToken, user: newUser } = response.data;
                
                user.value = newUser;
                token.value = newToken;
                isAuthenticated.value = true;
                
                localStorage.setItem('token', newToken);
                
                return response;
            } else {
                throw new Error(response.data.message);
            }
        } catch (err) {
            error.value = err.response?.data?.message || 'Đăng ký thất bại';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const login = async (email, password) => {
        loading.value = true;
        error.value = null;
        
        try {
            // Get CSRF cookie
            await sanctumApi.get('/sanctum/csrf-cookie');
            
            // Login request
            const response = await api.post('/login', {
                email,
                password
            });
            
            if (response.data.status === 'success') {
                const { token: newToken, user: newUser } = response.data;
                
                user.value = newUser;
                token.value = newToken;
                isAuthenticated.value = true;
                
                localStorage.setItem('token', newToken);
                
                return response;
            } else {
                throw new Error(response.data.message);
            }
        } catch (err) {
            error.value = err.response?.data?.message || 'Đăng nhập thất bại';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const logout = async () => {
        try {
            const response = await api.post('/logout');
            if (response.data.status === 'success') {
                user.value = null;
                token.value = null;
                isAuthenticated.value = false;
                localStorage.removeItem('token');
            } else {
                throw new Error(response.data.message);
            }
        } catch (err) {
            console.error('Logout error:', err);
            error.value = err.response?.data?.message || 'Đăng xuất thất bại';
        }
    };

    const checkAuth = async () => {
        if (!token.value) return;
        
        try {
            const response = await api.get('/user');
            user.value = response.data;
            isAuthenticated.value = true;
        } catch (err) {
            token.value = null;
            user.value = null;
            isAuthenticated.value = false;
            localStorage.removeItem('token');
            error.value = err.response?.data?.message || 'Kiểm tra xác thực thất bại';
        }
    };

    return {
        user,
        loading,
        error,
        token,
        isAuthenticated,
        register,
        login,
        logout,
        checkAuth
    };
}); 