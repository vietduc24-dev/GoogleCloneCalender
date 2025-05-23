<template>
  <div class="min-h-screen w-full flex items-center justify-center bg-gray-100 py-lg px-md sm:px-md lg:px-lg">
    <div class="w-[80rem] max-w-[90%]">
      <!-- Logo and Title -->
      <div class="text-center">
        <img src="https://www.gstatic.com/calendar/images/dynamiclogo_2020q4/calendar_31_2x.png" alt="Calendar Logo" class="h-[4.8rem] mx-auto mb-md"/>
        <h2 class="text-2xl font-semibold text-gray-800 mb-lg">
          Đăng nhập vào Calendar
        </h2>
      </div>

      <!-- Login Form -->
      <div class="bg-white p-lg rounded-lg shadow-lg w-full mx-auto">
        <form @submit.prevent="handleLogin" class="space-y-md max-w-[60rem] mx-auto" novalidate>
          <!-- Email Field -->
          <div class="relative h-[7.6rem]">
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-sm flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-gray-400">mail</span>
              </div>
              <input
                v-model="email"
                id="email"
                name="email"
                type="email"
                required
                class="w-full pl-[4rem] pr-md py-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400 text-base"
                placeholder="Nhập email của bạn"
                @invalid="$event.preventDefault()"
                :class="{'border-red-500': emailError}"
              />
            </div>
            <div class="h-[2rem] mt-xs">
              <span v-if="emailError" class="text-sm text-red-600">{{ emailError }}</span>
            </div>
          </div>

          <!-- Password Field -->
          <div class="relative h-[7.6rem]">
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-sm flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-gray-400">lock</span>
              </div>
              <input
                v-model="password"
                id="password"
                name="password"
                type="password"
                required
                class="w-full pl-[4rem] pr-md py-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400 text-base"
                placeholder="Nhập mật khẩu"
                @invalid="$event.preventDefault()"
                :class="{'border-red-500': passwordError}"
              />
            </div>
            <div class="h-[2rem] mt-xs">
              <span v-if="passwordError" class="text-sm text-red-600">{{ passwordError }}</span>
            </div>
          </div>

          <!-- Error Message -->
          <div v-if="authStore.error" class="bg-red-50 text-red-600 text-sm p-sm rounded-lg flex items-center">
            <span class="material-symbols-outlined mr-sm">error</span>
            {{ authStore.error }}
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="authStore.loading"
            class="w-full py-sm bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200 flex items-center justify-center space-x-sm"
          >
            <span class="material-symbols-outlined" v-if="!authStore.loading">login</span>
            <span v-if="authStore.loading" class="material-symbols-outlined animate-spin">progress_activity</span>
            <span>{{ authStore.loading ? 'Đang đăng nhập...' : 'Đăng nhập' }}</span>
          </button>
        </form>

        <!-- Register Link -->
        <p class="mt-md text-center text-sm text-gray-600">
          Chưa có tài khoản?
          <router-link to="/register" class="font-medium text-blue-600 hover:text-blue-500">
            Đăng ký ngay
          </router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useAuthStore } from '../../stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const email = ref('');
const password = ref('');
const emailError = ref('');
const passwordError = ref('');

// Validate email
const validateEmail = () => {
  if (!email.value) {
    emailError.value = 'Vui lòng nhập email';
    return false;
  }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
    emailError.value = 'Email không hợp lệ';
    return false;
  }
  emailError.value = '';
  return true;
};

// Validate password
const validatePassword = () => {
  if (!password.value) {
    passwordError.value = 'Vui lòng nhập mật khẩu';
    return false;
  }
  passwordError.value = '';
  return true;
};

// Clear errors when input changes
watch(email, () => {
  emailError.value = '';
});

watch(password, () => {
  passwordError.value = '';
});

const handleLogin = async () => {
  // Validate before submit
  const isEmailValid = validateEmail();
  const isPasswordValid = validatePassword();

  if (!isEmailValid || !isPasswordValid) {
    return;
  }

  try {
    const success = await authStore.login(email.value, password.value);

    // Chỉ redirect nếu chắc chắn là true
    if (success === true) {
      const redirectPath = router.currentRoute.value.query?.redirect || '/calendar';
      router.push(redirectPath);
    }
  } catch (error) {
    console.error('Đăng nhập thất bại:', error);
  }
};
</script>
