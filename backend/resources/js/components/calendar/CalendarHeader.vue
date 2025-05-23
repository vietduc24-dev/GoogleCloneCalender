<template>
  <header class="calendar-header flex items-center px-md py-sm border-b border-gray-200 lg:px-md lg:py-sm lg:min-w-[103rem]">
    <!-- Logo section -->
    <div class="flex items-center min-w-[16rem] lg:min-w-[18rem]">
      <button class="p-xs hover:bg-gray-100 rounded-full lg:p-sm">
        <svg class="w-[1.6rem] h-[1.6rem] text-gray-600 lg:w-[2rem] lg:h-[2rem]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      <img src="https://www.gstatic.com/calendar/images/dynamiclogo_2020q4/calendar_31_2x.png" alt="Calendar" class="h-[2rem] w-[2rem] ml-xs lg:h-[2.4rem] lg:w-[2.4rem] lg:ml-sm"/>
      <span class="text-sm text-gray-700 ml-xs lg:text-base lg:ml-sm">Lịch</span>
    </div>

    <!-- Navigation section -->
    <div class="flex items-center space-x-xs ml-xs flex-1 lg:space-x-sm lg:ml-md">
      <button 
        @click="$emit('today-click')"
        class="px-sm py-xs text-xs border border-gray-300 rounded hover:bg-gray-50 lg:px-sm lg:py-xs lg:text-sm"
      >
        Hôm nay
      </button>
      <div class="flex items-center space-x-xs lg:space-x-xs">
        <button 
          @click="$emit('prev-click')"
          class="p-xs hover:bg-gray-100 rounded-full lg:p-sm"
        >
          <span class="material-symbols-outlined nav-icon">chevron_left</span>
        </button>
        <button 
          @click="$emit('next-click')"
          class="p-xs hover:bg-gray-100 rounded-full lg:p-sm"
        >
          <span class="material-symbols-outlined nav-icon">chevron_right</span>
        </button>
      </div>
      <h2 class="text-xs font-normal text-gray-700 min-w-[14rem] ml-xs lg:text-base lg:min-w-[18rem] lg:ml-sm">{{ currentDate }}</h2>
    </div>

    <!-- Right section -->
    <div class="flex items-center space-x-xs min-w-[22rem] justify-end lg:space-x-sm lg:min-w-[26rem]">
      <!-- Search Icon -->
      <button class=" flex p-xs hover:bg-gray-100 rounded-full lg:p-sm">
        <span class="material-symbols-outlined header-icon">search</span>
      </button>
      <!-- Settings Icon -->
      <button class=" flex p-xs hover:bg-gray-100 rounded-full lg:p-sm">
        <span class="material-symbols-outlined header-icon">settings</span>
      </button>
      <button class="flex p-xs hover:bg-gray-100 rounded-full lg:p-sm">
        <span class="material-symbols-outlined header-icon">help</span>
      </button>
      
      <!-- View Mode Dropdown -->
      <div class="relative" v-click-outside="closeViewModeDropdown">
        <button 
          @click="toggleViewModeDropdown"
          class="flex items-center px-sm py-xs bg-gray-100 hover:bg-gray-200 rounded-lg text-gray-700 focus:outline-none lg:px-sm lg:py-xs"
        >
          <span class="text-xs lg:text-sm">{{ getCurrentViewLabel }}</span>
          <span class="material-symbols-outlined ml-xs header-icon lg:ml-xs" :class="{ 'rotate-180': showViewModeDropdown }">
            expand_more
          </span>
        </button>
        <!-- Dropdown Menu -->
        <div 
          v-if="showViewModeDropdown"
          class="absolute right-0 mt-xs py-xs w-[12rem] bg-white rounded-lg shadow-lg z-50 border border-gray-200 lg:mt-xs lg:py-xs lg:w-[14rem]"
        >
          <button
            v-for="mode in viewModes"
            :key="mode.value"
            @click="selectViewMode(mode.value)"
            class="w-full px-sm py-sm text-left text-xs hover:bg-gray-100 transition-colors lg:px-sm lg:py-sm lg:text-sm"
            :class="{ 'text-blue-600 bg-blue-50': currentViewMode === mode.value }"
          >
            {{ mode.label }}
          </button>
        </div>
      </div>
      
      <!-- User Profile -->
      <div class="relative ml-xs lg:ml-xs" v-click-outside="closeUserDropdown">
        <button 
          @click="toggleUserDropdown"
          class="flex items-center p-xs hover:bg-gray-100 rounded-lg focus:outline-none lg:p-xs"
        >
          <!-- User Email -->
          <span class="text-xs text-gray-600 mr-xs max-w-[8rem] truncate lg:text-sm lg:mr-xs lg:max-w-[12rem]">{{ user?.email || 'Loading...' }}</span>
          <!-- Avatar -->
          <div class="w-[2rem] h-[2rem] rounded-full bg-blue-500 flex items-center justify-center text-white text-[1rem] uppercase lg:w-[2.4rem] lg:h-[2.4rem] lg:text-sm">
            {{ userInitials }}
          </div>
        </button>
        <!-- User Dropdown Menu -->
        <div 
          v-if="showUserDropdown"
          class="absolute right-0 mt-xs py-xs w-[24rem] bg-white rounded-lg shadow-lg z-50 border border-gray-200 lg:mt-xs lg:py-xs lg:w-[28rem]"
        >
          <div class="px-sm py-sm border-b border-gray-200 lg:px-sm lg:py-sm">
            <div class="flex items-center space-x-sm">
              <div class="w-[3.2rem] h-[3.2rem] rounded-full bg-blue-500 flex items-center justify-center text-white text-sm uppercase lg:w-[4rem] lg:h-[4rem] lg:text-base">
                {{ userInitials }}
              </div>
              <div class="flex-1">
                <div class="text-xs font-medium text-gray-900 lg:text-sm">{{ user?.name }}</div>
                <div class="text-xs text-gray-500 lg:text-sm">{{ user?.email }}</div>
              </div>
            </div>
          </div>
          <div class="py-xs lg:py-xs">
            <a 
              href="#" 
              class="block px-sm py-sm text-xs text-gray-700 hover:bg-gray-100 lg:px-sm lg:py-sm lg:text-sm"
            >
              Quản lý tài khoản
            </a>
            <button
              class="w-full px-sm py-sm text-left text-xs text-red-600 hover:bg-red-50 lg:px-sm lg:py-sm lg:text-sm"
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
import { ref, computed, onMounted, watch } from 'vue';
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
    default: 'dayGridMonth'
  }
});

const showViewModeDropdown = ref(false);
const showUserDropdown = ref(false);
const currentViewMode = ref(props.currentView);

// Watch for props changes
watch(() => props.currentView, (newView) => {
  currentViewMode.value = newView;
});

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
  const mode = viewModes.find(mode => mode.value === currentViewMode.value);
  return mode ? mode.label : 'Tháng';
});

const toggleViewModeDropdown = () => {
  showViewModeDropdown.value = !showViewModeDropdown.value;
};

const closeViewModeDropdown = () => {
  showViewModeDropdown.value = false;
};

const selectViewMode = (value) => {
  currentViewMode.value = value;
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
/* Styles are now imported from resources/js/styles/components/header.scss */
</style> 