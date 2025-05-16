<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white max-w-md w-full p-8 rounded-lg shadow-lg">
      <!-- Logo and Title -->
      <div class="text-center mb-8">
        <img src="https://www.gstatic.com/calendar/images/dynamiclogo_2020q4/calendar_31_2x.png" alt="Calendar Logo" class="h-12 mx-auto mb-4"/>
        <h2 class="text-2xl font-semibold text-gray-800">Đăng nhập vào Calendar</h2>
      </div>

      <form @submit.prevent="handleLogin" class="space-y-6">
        <!-- Email Field -->
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="material-symbols-outlined text-gray-400">mail</span>
          </div>
          <input
            v-model="email"
            id="email"
            name="email"
            type="email"
            required
            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400 text-base"
            placeholder="Nhập email của bạn"
          />
        </div>

        <!-- Password Field -->
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="material-symbols-outlined text-gray-400">lock</span>
          </div>
          <input
            v-model="password"
            id="password"
            name="password"
            type="password"
            required
            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400 text-base"
            placeholder="Nhập mật khẩu"
          />
        </div>

        <!-- Error Message -->
        <div v-if="authStore.error" class="bg-red-50 text-red-600 text-sm p-3 rounded-lg flex items-center">
          <span class="material-symbols-outlined mr-2">error</span>
          {{ authStore.error }}
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="authStore.loading"
          class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200 flex items-center justify-center space-x-2"
        >
          <span class="material-symbols-outlined" v-if="!authStore.loading">login</span>
          <span v-if="authStore.loading" class="material-symbols-outlined animate-spin">progress_activity</span>
          <span>{{ authStore.loading ? 'Đang đăng nhập...' : 'Đăng nhập' }}</span>
        </button>
      </form>

      <!-- Register Link -->
      <p class="mt-6 text-center text-sm text-gray-600">
        Chưa có tài khoản?
        <router-link to="/register" class="font-medium text-blue-600 hover:text-blue-500">
          Đăng ký ngay
        </router-link>
      </p>
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

<style scoped>
.material-symbols-outlined {
  font-variation-settings:
  'FILL' 0,
  'wght' 400,
  'GRAD' 0,
  'opsz' 24;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}
</style>
