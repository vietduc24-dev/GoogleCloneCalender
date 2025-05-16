import axios from 'axios';

// Cấu hình chung cho tất cả instance axios
const axiosConfig = {
    withCredentials: true,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
};

// Instance cho sanctum authentication
export const sanctumApi = axios.create({
    ...axiosConfig,
    baseURL: 'http://localhost:8000',  // Updated port
});

// Instance cho API chung
export const api = axios.create({
    ...axiosConfig,
    baseURL: 'http://localhost:8000/api',  // Updated port
});

// Interceptor để thêm token vào header
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        
        // Get CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
            config.headers['X-CSRF-TOKEN'] = csrfToken;
        }
        
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Interceptor để xử lý response
api.interceptors.response.use(
    (response) => response,
    async (error) => {
        if (error.response?.status === 401) {
            // Xử lý khi token hết hạn
            localStorage.removeItem('token');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

export default api;