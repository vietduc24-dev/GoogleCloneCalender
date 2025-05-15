import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from 'tailwindcss';
import autoprefixer from 'autoprefixer';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/main.js'],
            refresh: true,
        }),
        vue(),
    ],
    css: {
        postcss: {
            plugins: [
                tailwindcss,
                autoprefixer,
            ],
        },
    },
    define: {
        'process.env': {
            VITE_APP_URL: JSON.stringify('http://localhost:8000'),
            VITE_API_URL: JSON.stringify('http://localhost:8000/api')
        }
    },
    server: {
        host: 'localhost',
        port: 5173,
        proxy: {
            '/api': {
                target: 'http://localhost:8000',
                changeOrigin: true,
                secure: false
            },
            '/sanctum/csrf-cookie': {
                target: 'http://localhost:8000',
                changeOrigin: true,
                secure: false
            }
        }
    }
});
