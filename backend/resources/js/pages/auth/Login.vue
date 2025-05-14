<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white max-w-sm w-full p-8 rounded-lg shadow">
      <h2 class="text-center text-2xl font-semibold mb-8">Login</h2>
      <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
          <input
            v-model="email"
            id="email"
            name="email"
            type="email"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder-gray-400 text-base"
            placeholder="Enter your email"
          />
        </div>
        <div>
          <input
            v-model="password"
            id="password"
            name="password"
            type="password"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder-gray-400 text-base"
            placeholder="Enter your password"
          />
        </div>
        <div v-if="authStore.error" class="text-red-600 text-sm text-center">
          {{ authStore.error }}
        </div>
        <button
          type="submit"
          :disabled="authStore.loading"
          class="w-full py-3 mt-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-md transition disabled:opacity-50"
        >
          <span v-if="authStore.loading">Signing in...</span>
          <span v-else>Sign In</span>
        </button>
      </form>
      <div class="mt-6 text-center">
        <a href="/register" class="text-blue-500 text-sm hover:underline">Create your account?</a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../../stores/auth';

const authStore = useAuthStore();
const email = ref('');
const password = ref('');
const loginError = ref('');

const handleLogin = async () => {
  try {
    loginError.value = '';
    await authStore.login(email.value, password.value);
    window.location.href = '/calendar';
  } catch (error) {
    loginError.value = error.response?.data?.message || 'Có lỗi xảy ra khi đăng nhập';
  }
};
</script>
