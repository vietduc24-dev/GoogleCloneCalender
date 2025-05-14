<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <Sidebar
      :view="view"
      :selectedDate="selectedDate"
      :setView="setView"
      :setSelectedDate="setSelectedDate"
      :setCurrentDate="setCurrentDate"
    />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header -->
      <header class="bg-white border-b border-gray-200 p-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <button
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
              @click="goToToday"
            >
              Today
            </button>
            <div class="flex items-center space-x-2">
              <button
                class="p-1 rounded-full hover:bg-gray-100"
                @click="previousPeriod"
              >
                <span class="sr-only">Previous</span>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
              </button>
              <button
                class="p-1 rounded-full hover:bg-gray-100"
                @click="nextPeriod"
              >
                <span class="sr-only">Next</span>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </button>
            </div>
            <h2 class="text-lg font-semibold text-gray-900">
              {{ formatCurrentPeriod }}
            </h2>
          </div>
        </div>
      </header>

      <!-- Calendar Grid -->
      <main class="flex-1 overflow-hidden">
        <CalendarGrid
          :currentDate="currentDate"
          :events="events"
          :view="view"
        />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import Sidebar from '../../components/calendar/Sidebar.vue';
import CalendarGrid from '../../components/calendar/CalendarGrid.vue';

// State
const view = ref('week');
const currentDate = ref(new Date());
const selectedDate = ref(new Date());

// Sample events data (replace with actual data from your backend)
const events = ref([
  {
    id: 1,
    title: 'Team Meeting',
    start: new Date(2024, 2, 20, 10, 0),
    end: new Date(2024, 2, 20, 11, 0),
    color: '#4285F4'
  },
  // Add more events as needed
]);

// Methods
const setView = (newView) => {
  view.value = newView;
};

const setSelectedDate = (date) => {
  selectedDate.value = date;
};

const setCurrentDate = (date) => {
  currentDate.value = date;
};

const goToToday = () => {
  currentDate.value = new Date();
  selectedDate.value = new Date();
};

const previousPeriod = () => {
  const date = new Date(currentDate.value);
  if (view.value === 'day') {
    date.setDate(date.getDate() - 1);
  } else if (view.value === 'week') {
    date.setDate(date.getDate() - 7);
  } else if (view.value === 'month') {
    date.setMonth(date.getMonth() - 1);
  }
  currentDate.value = date;
};

const nextPeriod = () => {
  const date = new Date(currentDate.value);
  if (view.value === 'day') {
    date.setDate(date.getDate() + 1);
  } else if (view.value === 'week') {
    date.setDate(date.getDate() + 7);
  } else if (view.value === 'month') {
    date.setMonth(date.getMonth() + 1);
  }
  currentDate.value = date;
};

// Computed
const formatCurrentPeriod = computed(() => {
  const options = { month: 'long', year: 'numeric' };
  if (view.value === 'day') {
    options.day = 'numeric';
  }
  return currentDate.value.toLocaleDateString('en-US', options);
});
</script>

<style scoped>
.calendar-grid {
  height: calc(100vh - 4rem); /* Adjust based on your header height */
}
</style>
