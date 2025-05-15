<template>
  <div class="w-64 flex flex-col border-r border-gray-200">
    <!-- Create Button -->
    <div class="p-4">
      <button
        @click="$emit('create-click')"
        class="flex items-center justify-center w-36 h-12 rounded-2xl shadow-md hover:shadow-lg bg-white border border-gray-300 transition-shadow"
      >
        <svg class="w-6 h-6 text-gray-600 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <span class="text-sm font-medium">Create</span>
      </button>
    </div>

    <!-- Mini Calendar Container -->
    <div class="px-4 pb-4">
      <MiniCalendar
        :events="events"
        :onSelect="handleMiniCalendarSelect"
      />
    </div>

    <!-- Display Options Section -->
    <div class="px-4 py-2 border-t border-gray-200">
      <div class="flex items-center justify-between mb-2">
        <h3 class="text-sm font-medium text-gray-700">Lịch trình của tôi</h3>
      </div>
      <div class="space-y-2">
        <div class="flex items-center">
          <input
            type="checkbox"
            :checked="showEvents"
            @change="$emit('toggle-display', { type: 'events', value: !showEvents })"
            class="h-4 w-4 text-blue-600 rounded-sm cursor-pointer"
          />
          <span class="ml-3 text-sm text-gray-700">Sự kiện</span>
        </div>
        <div class="flex items-center">
          <input
            type="checkbox"
            :checked="showReminders"
            @change="$emit('toggle-display', { type: 'reminders', value: !showReminders })"
            class="h-4 w-4 text-blue-600 rounded-sm cursor-pointer"
          />
          <span class="ml-3 text-sm text-gray-700">Lời nhắc</span>
        </div>
      </div>
    </div>

    
  </div>
</template>

<script setup>
import MiniCalendar from './MiniCalendar.vue';

const props = defineProps({
  calendars: {
    type: Array,
    required: true
  },
  selectedCalendar: {
    type: Object,
    default: null
  },
  events: {
    type: Array,
    default: () => []
  },
  showEvents: {
    type: Boolean,
    default: true
  },
  showReminders: {
    type: Boolean,
    default: true
  }
});

const emit = defineEmits(['create-click', 'toggle-calendar', 'mini-calendar-select', 'toggle-display']);

const isCalendarSelected = (calendar) => {
  return props.selectedCalendar?.id === calendar.id;
};

const handleMiniCalendarSelect = (selectInfo) => {
  emit('mini-calendar-select', selectInfo);
};
</script> 