<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
      <!-- Logo and Title -->
      <div class="text-center">
        <img src="https://www.gstatic.com/calendar/images/dynamiclogo_2020q4/calendar_31_2x.png" alt="Calendar Logo" class="h-12 mx-auto mb-4"/>
        <h2 class="text-2xl font-semibold text-gray-800 mb-8">
          Đăng ký tài khoản mới
        </h2>
      </div>

      <!-- Registration Form -->
      <div class="bg-white p-8 rounded-lg shadow-lg">
        <form class="space-y-6" @submit.prevent="handleRegister">
          <!-- Name Field -->
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <span class="material-symbols-outlined text-gray-400">person</span>
            </div>
            <input
              v-model="name"
              id="name"
              name="name"
              type="text"
              required
              class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400"
              placeholder="Họ và tên"
            />
          </div>

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
              class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400"
              placeholder="Email"
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
              class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400"
              placeholder="Mật khẩu"
            />
            <p class="mt-2 text-xs text-gray-500 flex items-center">
              <span class="material-symbols-outlined text-gray-400 mr-1" style="font-size: 14px;">info</span>
              Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt
            </p>
          </div>

          <!-- Password Confirmation Field -->
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <span class="material-symbols-outlined text-gray-400">key</span>
            </div>
            <input
              v-model="passwordConfirmation"
              id="password_confirmation"
              name="password_confirmation"
              type="password"
              required
              class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400"
              placeholder="Xác nhận mật khẩu"
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
            <span class="material-symbols-outlined" v-if="!authStore.loading">person_add</span>
            <span v-if="authStore.loading" class="material-symbols-outlined animate-spin">progress_activity</span>
            <span>{{ authStore.loading ? 'Đang đăng ký...' : 'Đăng ký' }}</span>
          </button>
        </form>

        <!-- Login Link -->
        <p class="mt-6 text-center text-sm text-gray-600">
          Đã có tài khoản?
          <router-link to="/login" class="font-medium text-blue-600 hover:text-blue-500">
            Đăng nhập ngay
          </router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const name = ref('');
const email = ref('');
const password = ref('');
const passwordConfirmation = ref('');

const handleRegister = async () => {
  try {
    await authStore.register({
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
    });

    // Nếu đăng ký thành công, chuyển hướng đến trang login
    router.push('/login');
  } catch (error) {
    // Lỗi đã được xử lý trong auth store
    console.error('Registration error:', error);
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
