<template>
    <div class="calendar-grid h-full overflow-y-auto">
      <!-- Days header -->
      <div class="sticky top-0 z-10 grid grid-cols-8 border-b border-gray-200 bg-white">
        <div class="border-r border-gray-200 w-16"></div>
        <div
          v-for="(day, index) in weekDays"
          :key="index"
          class="px-2 py-3 text-center border-r border-gray-200"
          :class="{ 'bg-blue-50': isToday(day) }"
        >
          <div class="text-xs text-gray-500">
            {{ formatDayName(day) }}
          </div>
          <div class="text-sm font-medium" :class="{ 'text-blue-600': isToday(day) }">
            {{ day.getDate() }}
          </div>
        </div>
      </div>
  
      <!-- Time slots -->
      <div class="grid grid-cols-8">
        <!-- Time labels -->
        <div class="col-span-1">
          <div
            v-for="(time, index) in timeSlots"
            :key="index"
            class="h-16 border-b border-r border-gray-200 text-xs text-gray-500 relative"
          >
            <span class="absolute -top-2 right-2">{{ time }}</span>
          </div>
        </div>
  
        <!-- Calendar cells -->
        <div class="col-span-7 grid grid-cols-7">
          <div v-for="(day, dayIndex) in weekDays" :key="dayIndex" class="col-span-1">
            <div
              v-for="(slot, hourIndex) in timeSlots"
              :key="hourIndex"
              class="h-16 border-b border-r border-gray-200 relative"
              :class="{ 'bg-blue-50/30': isToday(day) }"
            >
              <Event
                v-for="(event, eventIndex) in getEventsForTimeSlot(day, hourIndex)"
                :key="eventIndex"
                :event="event"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { computed } from 'vue';
  import Event from './Event.vue';
  
  // Define props with validation
  const props = defineProps({
    currentDate: {
      type: Date,
      required: true
    },
    events: {
      type: Array,
      default: () => []
    },
    view: {
      type: String,
      default: 'week',
      validator: (value) => ['day', 'week', 'month'].includes(value)
    }
  });
  
  // Get the start of the week (Sunday)
  const getStartOfWeek = (date) => {
    const result = new Date(date);
    const day = result.getDay();
    result.setDate(result.getDate() - day);
    return result;
  };
  
  // Generate time slots
  const timeSlots = Array.from({ length: 24 }, (_, i) => {
    const hour = i;
    return `${hour === 0 || hour === 12 ? 12 : hour % 12}${hour < 12 ? 'am' : 'pm'}`;
  });
  
  // Format day name
  const formatDayName = (date) =>
    date.toLocaleDateString('en-US', { weekday: 'short' });
  
  // Check if a date is today
  const isToday = (date) => {
    const today = new Date();
    return (
      date.getDate() === today.getDate() &&
      date.getMonth() === today.getMonth() &&
      date.getFullYear() === today.getFullYear()
    );
  };
  
  // Generate days of the week
  const weekDays = computed(() => {
    const startOfWeek = getStartOfWeek(props.currentDate);
    return Array.from({ length: 7 }, (_, i) => {
      const day = new Date(startOfWeek);
      day.setDate(day.getDate() + i);
      return day;
    });
  });
  
  // Filter events for current week
  const filterEventsForWeek = () => {
    const startOfWeek = getStartOfWeek(props.currentDate);
    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(endOfWeek.getDate() + 7);
    return props.events.filter((event) => {
      const eventStart = new Date(event.start);
      return eventStart >= startOfWeek && eventStart < endOfWeek;
    });
  };
  
  // Get events for a specific day and hour
  const getEventsForTimeSlot = (day, hourIndex) => {
    return filterEventsForWeek().filter((event) => {
      const eventDate = new Date(event.start);
      return (
        eventDate.getDate() === day.getDate() &&
        eventDate.getMonth() === day.getMonth() &&
        eventDate.getFullYear() === day.getFullYear() &&
        eventDate.getHours() === hourIndex
      );
    });
  };
  </script>
  