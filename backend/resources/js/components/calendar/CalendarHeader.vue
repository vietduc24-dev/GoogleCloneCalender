<template>
  <header class="flex items-center px-6 py-3 border-b border-gray-200">
    <div class="flex items-center">
      <button class="p-2 hover:bg-gray-100 rounded-full">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      <img src="https://www.gstatic.com/calendar/images/dynamiclogo_2020q4/calendar_31_2x.png" alt="Calendar" class="h-8 w-8 ml-2"/>
      <span class="text-xl text-gray-700 ml-2">Lịch</span>
    </div>

    <div class="flex items-center space-x-2 ml-8">
      <button 
        @click="$emit('today-click')"
        class="px-4 py-1 text-sm border border-gray-300 rounded hover:bg-gray-50"
      >
        Hôm nay
      </button>
      <div class="flex items-center space-x-1">
        <button 
          @click="$emit('prev-click')"
          class="p-2 hover:bg-gray-100 rounded-full"
        >
          <span class="material-symbols-outlined">chevron_left</span>
        </button>
        <button 
          @click="$emit('next-click')"
          class="p-2 hover:bg-gray-100 rounded-full"
        >
          <span class="material-symbols-outlined">chevron_right</span>
        </button>
      </div>
      <h2 class="text-lg font-normal text-gray-700">{{ currentDate }}</h2>
    </div>

    <div class="flex items-center ml-auto space-x-4">
      <!-- Search Icon -->
      <button class="p-2 hover:bg-gray-100 rounded-full">
        <span class="material-symbols-outlined text-gray-600">search</span>
      </button>
      <!-- Settings Icon -->
      <button class="p-2 hover:bg-gray-100 rounded-full">
        <span class="material-symbols-outlined text-gray-600">settings</span>
      </button>
      <button class="p-2 hover:bg-gray-100 rounded-full">
        <span class="material-symbols-outlined text-gray-600">help</span>
      </button>
      <!-- View Mode Dropdown -->
      <div class="relative" v-click-outside="closeViewModeDropdown">
        <button 
          @click="toggleViewModeDropdown"
          class="flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-gray-700 focus:outline-none"
        >
          <span>{{ getCurrentViewLabel }}</span>
          <span class="material-symbols-outlined ml-1" :class="{ 'rotate-180': showViewModeDropdown }">
            expand_more
          </span>
        </button>
        <!-- Dropdown Menu -->
        <div 
          v-if="showViewModeDropdown"
          class="absolute right-0 mt-2 py-2 w-40 bg-white rounded-lg shadow-lg z-50 border border-gray-200"
        >
          <button
            v-for="mode in viewModes"
            :key="mode.value"
            @click="selectViewMode(mode.value)"
            class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 transition-colors"
            :class="{ 'text-blue-600 bg-blue-50': currentView === mode.value }"
          >
            {{ mode.label }}
          </button>
        </div>
      </div>
      
      <!-- User Profile -->
      <div class="relative" v-click-outside="closeUserDropdown">
        <button 
          @click="toggleUserDropdown"
          class="flex items-center space-x-2 p-2 hover:bg-gray-100 rounded-lg focus:outline-none"
        >
          <!-- User Email -->
          <span class="text-sm text-gray-600 mr-2">{{ user?.email || 'Loading...' }}</span>
          <!-- Avatar -->
          <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white uppercase">
            {{ userInitials }}
          </div>
        </button>
        <!-- User Dropdown Menu -->
        <div 
          v-if="showUserDropdown"
          class="absolute right-0 mt-2 py-2 w-80 bg-white rounded-lg shadow-lg z-50 border border-gray-200"
        >
          <div class="px-4 py-3 border-b border-gray-200">
            <div class="flex items-center space-x-3">
              <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white text-xl uppercase">
                {{ userInitials }}
              </div>
              <div class="flex-1">
                <div class="text-sm font-medium text-gray-900">{{ user?.name }}</div>
                <div class="text-sm text-gray-500">{{ user?.email }}</div>
              </div>
            </div>
          </div>
          <div class="py-1">
            <a 
              href="#" 
              class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
            >
              Quản lý tài khoản
            </a>
            <button
              class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50"
              @click="handleLogout"
            >
              Đăng xuất
            </button>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const props = defineProps({
  currentDate: {
    type: String,
    required: true
  },
  currentView: {
    type: String,
    default: 'month'
  }
});

const showViewModeDropdown = ref(false);
const showUserDropdown = ref(false);

// Get user from auth store
const user = computed(() => authStore.user);

// Check auth on component mount
onMounted(async () => {
  if (!authStore.isAuthenticated) {
    await authStore.checkAuth();
  }
});

const userInitials = computed(() => {
  if (!user.value?.name) return '?';
  return user.value.name
    .split(' ')
    .map(word => word[0])
    .join('')
    .toUpperCase()
    .slice(0, 2);
});

const viewModes = [
  { label: 'Ngày', value: 'timeGridDay' },
  { label: 'Tuần', value: 'timeGridWeek' },
  { label: 'Tháng', value: 'dayGridMonth' },
];

const emit = defineEmits(['today-click', 'prev-click', 'next-click', 'search', 'view-change']);

const getCurrentViewLabel = computed(() => {
  const mode = viewModes.find(mode => mode.value === props.currentView);
  return mode ? mode.label : 'Tháng';
});

const toggleViewModeDropdown = () => {
  showViewModeDropdown.value = !showViewModeDropdown.value;
};

const closeViewModeDropdown = () => {
  showViewModeDropdown.value = false;
};

const selectViewMode = (value) => {
  emit('view-change', value);
  closeViewModeDropdown();
};

const toggleUserDropdown = () => {
  showUserDropdown.value = !showUserDropdown.value;
};

const closeUserDropdown = () => {
  showUserDropdown.value = false;
};

const handleLogout = async () => {
  try {
    await authStore.logout();
    router.push('/login');
  } catch (error) {
    console.error('Logout failed:', error);
  }
};

// Click outside directive
const vClickOutside = {
  mounted(el, binding) {
    el.clickOutsideEvent = (event) => {
      if (!(el === event.target || el.contains(event.target))) {
        binding.value();
      }
    };
    document.addEventListener('click', el.clickOutsideEvent);
  },
  unmounted(el) {
    document.removeEventListener('click', el.clickOutsideEvent);
  },
};
</script>

<style scoped>
.material-symbols-outlined {
  font-variation-settings:
  'FILL' 0,
  'wght' 400,
  'GRAD' 0,
  'opsz' 24;
  font-size: 20px;
}

.rotate-180 {
  transform: rotate(180deg);
}
</style> 