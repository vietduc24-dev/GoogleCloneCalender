<template>
  <div class="min-h-screen w-full flex items-center justify-center bg-gray-50 py-lg px-md sm:px-md lg:px-lg">
    <!-- Success Notification -->
    <div v-if="showSuccess" class="fixed top-md right-md bg-green-50 text-green-800 p-md rounded-lg shadow-lg flex items-center space-x-sm z-50 animate-fade-in">
      <span class="material-symbols-outlined text-green-600">check_circle</span>
      <div>
        <h3 class="font-medium">Đăng ký thành công!</h3>
        <p class="text-sm">Vui lòng đăng nhập để tiếp tục.</p>
      </div>
      <button @click="showSuccess = false" class="text-green-600 hover:text-green-800">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>

    <div class="w-[80rem] max-w-[90%]">
      <!-- Logo and Title -->
      <div class="text-center">
        <img src="https://www.gstatic.com/calendar/images/dynamiclogo_2020q4/calendar_31_2x.png" alt="Calendar Logo" class="h-[4.8rem] mx-auto mb-md"/>
        <h2 class="text-2xl font-semibold text-gray-800 mb-lg">
          Đăng ký tài khoản mới
        </h2>
      </div>

      <!-- Registration Form -->
      <div class="bg-white p-lg rounded-lg shadow-lg w-full mx-auto">
        <form class="space-y-md max-w-[60rem] mx-auto" @submit.prevent="handleRegister" novalidate>
          <!-- Name Field -->
          <div class="relative h-[7.6rem]">
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-sm flex items-center">
                <span class="material-symbols-outlined text-gray-400">person</span>
              </div>
              <input
                v-model="name"
                id="name"
                name="name"
                type="text"
                required
                class="w-full pl-[4rem] pr-md py-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400"
                placeholder="Họ và tên"
                @invalid="$event.preventDefault()"
                :class="{'border-red-500': nameError}"
              />
            </div>
            <div class="h-[2rem] mt-xs">
              <span v-if="nameError" class="text-sm text-red-600">{{ nameError }}</span>
            </div>
          </div>

          <!-- Email Field -->
          <div class="relative h-[7.6rem] w-full">
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-sm flex items-center">
                <span class="material-symbols-outlined text-gray-400">mail</span>
              </div>
              <input
                v-model="email"
                id="email"
                name="email"
                type="email"
                required
                class="w-full pl-[4rem] pr-md py-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400"
                placeholder="Email"
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
              <div class="absolute inset-y-0 left-0 pl-sm flex items-center">
                <span class="material-symbols-outlined text-gray-400">lock</span>
              </div>
              <input
                v-model="password"
                id="password"
                name="password"
                type="password"
                required
                class="w-full pl-[4rem] pr-md py-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400"
                placeholder="Mật khẩu"
                @invalid="$event.preventDefault()"
                :class="{'border-red-500': passwordError}"
              />
            </div>
            <div class="h-[2rem] mt-xs">
              <span v-if="passwordError" class="text-sm text-red-600">{{ passwordError }}</span>
            </div>
          </div>

          <!-- Password Confirmation Field -->
          <div class="relative h-[7.6rem]">
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-sm flex items-center">
                <span class="material-symbols-outlined text-gray-400">key</span>
              </div>
              <input
                v-model="passwordConfirmation"
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                required
                class="w-full pl-[4rem] pr-md py-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400"
                placeholder="Xác nhận mật khẩu"
                @invalid="$event.preventDefault()"
                :class="{'border-red-500': passwordConfirmationError}"
              />
            </div>
            <div class="h-[2rem] mt-xs">
              <span v-if="passwordConfirmationError" class="text-sm text-red-600">{{ passwordConfirmationError }}</span>
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
            <span class="material-symbols-outlined" v-if="!authStore.loading">person_add</span>
            <span v-if="authStore.loading" class="material-symbols-outlined animate-spin">progress_activity</span>
            <span>{{ authStore.loading ? 'Đang đăng ký...' : 'Đăng ký' }}</span>
          </button>
        </form>

        <!-- Login Link -->
        <p class="mt-md text-center text-sm text-gray-600">
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
import { ref, watch } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const name = ref('');
const email = ref('');
const password = ref('');
const passwordConfirmation = ref('');
const showSuccess = ref(false);

// Error states
const nameError = ref('');
const emailError = ref('');
const passwordError = ref('');
const passwordConfirmationError = ref('');

// Validation functions
const validateName = () => {
  if (!name.value) {
    nameError.value = 'Vui lòng nhập họ và tên';
    return false;
  }
  if (name.value.length < 2) {
    nameError.value = 'Tên phải có ít nhất 2 ký tự';
    return false;
  }
  nameError.value = '';
  return true;
};

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

const validatePassword = () => {
  if (!password.value) {
    passwordError.value = 'Vui lòng nhập mật khẩu';
    return false;
  }
  if (password.value.length < 6) {
    passwordError.value = 'Mật khẩu phải có ít nhất 6 ký tự';
    return false;
  }
  passwordError.value = '';
  return true;
};

const validatePasswordConfirmation = () => {
  if (!passwordConfirmation.value) {
    passwordConfirmationError.value = 'Vui lòng xác nhận mật khẩu';
    return false;
  }
  if (passwordConfirmation.value !== password.value) {
    passwordConfirmationError.value = 'Mật khẩu xác nhận không khớp';
    return false;
  }
  passwordConfirmationError.value = '';
  return true;
};

// Clear errors on input change
watch(name, () => nameError.value = '');
watch(email, () => emailError.value = '');
watch(password, () => {
  passwordError.value = '';
  if (passwordConfirmation.value) {
    validatePasswordConfirmation();
  }
});
watch(passwordConfirmation, () => passwordConfirmationError.value = '');

const handleRegister = async () => {
  // Validate all fields
  const isNameValid = validateName();
  const isEmailValid = validateEmail();
  const isPasswordValid = validatePassword();
  const isPasswordConfirmationValid = validatePasswordConfirmation();

  if (!isNameValid || !isEmailValid || !isPasswordValid || !isPasswordConfirmationValid) {
    return;
  }

  try {
    await authStore.register({
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
    });

    // Show success notification
    showSuccess.value = true;

    // Wait for 2 seconds before redirecting
    setTimeout(() => {
      router.push('/login');
    }, 2000);

  } catch (error) {
    console.error('Registration error:', error);
  }
};
</script>
