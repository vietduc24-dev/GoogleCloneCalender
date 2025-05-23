import { defineStore } from 'pinia';
import { ref } from 'vue';
import { sanctumApi, api } from '../utils/axios';

// Cấu hình chung cho axios
const configureAxios = (axiosInstance) => {
    // Request interceptor
    axiosInstance.interceptors.request.use(config => {
        config.headers['X-Requested-With'] = 'XMLHttpRequest';
        config.headers['Accept'] = 'application/json';
        config.headers['Content-Type'] = 'application/json';
        // Thêm flag để ngăn redirect
        config.headers['X-Inertia'] = true;
        return config;
    });

    // Response interceptor
    axiosInstance.interceptors.response.use(
        response => response,
        error => {
            if (error.response?.status === 401) {
                return Promise.reject({
                    response: {
                        data: {
                            message: 'Tài khoản hoặc mật khẩu không đúng'
                        }
                    }
                });
            }
            return Promise.reject(error);
        }
    );
};

// Áp dụng cấu hình cho cả hai instance
configureAxios(api);
configureAxios(sanctumApi);

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
            const response = await api.post('/register', userData, {
                headers: {
                    'X-No-Redirect': true
                }
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
            await sanctumApi.get('/sanctum/csrf-cookie');
    
            const response = await api.post('/login', { 
                email, 
                password 
            }, {
                headers: {
                    'X-No-Redirect': true
                },
                validateStatus: function (status) {
                    return status >= 200 && status < 500; // Chấp nhận status 401
                }
            });
    
            if (!response.data || response.data.status !== 'success') {
                error.value = response.data?.message || 'Đăng nhập thất bại';
                return false;
            }
    
            const { token: newToken, user: newUser } = response.data;
    
            user.value = newUser;
            token.value = newToken;
            isAuthenticated.value = true;
            localStorage.setItem('token', newToken);
    
            return true;
        } catch (err) {
            // Clear state nếu có lỗi
            user.value = null;
            token.value = null;
            isAuthenticated.value = false;
            localStorage.removeItem('token');
    
            // Gán lỗi
            error.value = err.response?.data?.message || 'Tài khoản hoặc mật khẩu không đúng';
            return false;
        } finally {
            loading.value = false;
        }
    };

    const logout = async () => {
        loading.value = true;
        error.value = null;
        
        try {
            const response = await api.post('/logout');
            
            if (response.data.status === 'success') {
                // Clear user data
                user.value = null;
                token.value = null;
                isAuthenticated.value = false;
                localStorage.removeItem('token');
                
                return response;
            } else {
                throw new Error(response.data.message);
            }
        } catch (err) {
            error.value = err.response?.data?.message || 'Đăng xuất thất bại';
            
            // If we get a 401 error, the token is invalid/expired, so clear the data anyway
            if (err.response?.status === 401) {
                user.value = null;
                token.value = null;
                isAuthenticated.value = false;
                localStorage.removeItem('token');
            }
            
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const checkAuth = async () => {
        if (!token.value) {
            isAuthenticated.value = false;
            user.value = null;
            return false;
        }
        
        try {
            const response = await api.get('/user');
            user.value = response.data;
            isAuthenticated.value = true;
            return true;
        } catch (err) {
            token.value = null;
            user.value = null;
            isAuthenticated.value = false;
            localStorage.removeItem('token');
            error.value = err.response?.data?.message || 'Phiên đăng nhập đã hết hạn';
            return false;
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