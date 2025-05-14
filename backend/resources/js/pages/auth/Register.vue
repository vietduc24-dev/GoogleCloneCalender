<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Đăng ký tài khoản mới
        </h2>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="handleRegister">
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="name" class="sr-only">Họ và tên</label>
            <input
              v-model="name"
              id="name"
              name="name"
              type="text"
              required
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Họ và tên"
            />
          </div>
          <div>
            <label for="email" class="sr-only">Email</label>
            <input
              v-model="email"
              id="email"
              name="email"
              type="email"
              required
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Email"
            />
          </div>
          <div>
            <label for="password" class="sr-only">Mật khẩu</label>
            <input
              v-model="password"
              id="password"
              name="password"
              type="password"
              required
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Mật khẩu"
            />
          </div>
          <div>
            <label for="password_confirmation" class="sr-only">Xác nhận mật khẩu</label>
            <input
              v-model="passwordConfirmation"
              id="password_confirmation"
              name="password_confirmation"
              type="password"
              required
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Xác nhận mật khẩu"
            />
          </div>
        </div>

        <div v-if="errorMessage" class="text-red-600 text-center text-sm">
          {{ errorMessage }}
        </div>

        <div v-if="successMessage" class="text-green-600 text-center text-sm">
          {{ successMessage }}
        </div>

        <div>
          <button
            type="submit"
            :disabled="loading"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
          >
            <span v-if="loading">Đang đăng ký...</span>
            <span v-else>Đăng ký</span>
          </button>
        </div>
      </form>

      <p class="mt-4 text-center text-sm text-gray-600">
        Đã có tài khoản?
        <a href="/login" class="font-medium text-indigo-600 hover:text-indigo-500">Đăng nhập</a>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const name = ref('');
const email = ref('');
const password = ref('');
const passwordConfirmation = ref('');

const loading = ref(false);
const errorMessage = ref('');
const successMessage = ref('');

// Giả lập API đăng ký (thay bằng API thật của bạn)
const fakeRegisterApi = (data) => {
  return new Promise((resolve, reject) => {
    setTimeout(() => {
      if (data.password !== data.password_confirmation) {
        reject({ message: 'Mật khẩu xác nhận không khớp' });
      } else if (!data.email.includes('@')) {
        reject({ message: 'Email không hợp lệ' });
      } else {
        resolve({ message: 'Đăng ký thành công!' });
      }
    }, 1500);
  });
};

const handleRegister = async () => {
  errorMessage.value = '';
  successMessage.value = '';
  loading.value = true;

  try {
    const res = await fakeRegisterApi({
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
    });
    successMessage.value = res.message;
    // Bạn có thể chuyển hướng sang trang login hoặc dashboard ở đây
    // window.location.href = '/login';
  } catch (error) {
    errorMessage.value = error.message || 'Có lỗi xảy ra khi đăng ký';
  } finally {
    loading.value = false;
  }
};
</script>
