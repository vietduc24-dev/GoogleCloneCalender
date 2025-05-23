<template>
  <div class="w-[25.6rem] min-w-[25.6rem] flex flex-col border-r border-gray-200 h-full overflow-y-auto">
    <!-- Create Button -->
    <div class="p-md">
      <button
        @click="$emit('create-click')"
        class="flex items-center justify-center w-[14.4rem] h-[4.8rem] rounded-[2rem] shadow-md hover:shadow-lg bg-white border border-gray-300 transition-shadow"
      >
        <svg class="w-[2.4rem] h-[2.4rem] text-gray-600 mr-sm" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <span class="text-sm font-medium">Create</span>
      </button>
    </div>

    <!-- Mini Calendar Container -->
    <div class="px-md pb-md min-h-[30rem]">
      <MiniCalendar
        :events="events"
        :onSelect="handleMiniCalendarSelect"
      />
    </div>

    <!-- Display Options Section -->
    <div class="px-md py-sm border-t border-gray-200">
      <div class="flex items-center justify-between mb-sm">
        <h3 class="text-sm font-medium text-gray-700">Lịch trình của tôi</h3>
      </div>
      <div class="space-y-sm">
        <div class="flex items-center">
          <input
            type="checkbox"
            :checked="showEvents"
            @change="$emit('toggle-display', { type: 'events', value: !showEvents })"
            class="h-[1.6rem] w-[1.6rem] text-blue-600 rounded-sm cursor-pointer"
          />
          <span class="ml-xs text-sm text-gray-700">Sự kiện</span>
        </div>
        <div class="flex items-center">
          <input
            type="checkbox"
            :checked="showReminders"
            @change="$emit('toggle-display', { type: 'reminders', value: !showReminders })"
            class="h-[1.6rem] w-[1.6rem] text-blue-600 rounded-sm cursor-pointer"
          />
          <span class="ml-xs text-sm text-gray-700">Lời nhắc</span>
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