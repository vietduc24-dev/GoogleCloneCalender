<template>
    <div class="hidden md:flex flex-col w-60 border-r border-gray-200 bg-white p-4 overflow-y-auto">
      <!-- Mini Calendar -->
      <div class="mb-6">
        <MiniCalendar
          :selectedDate="selectedDate"
          @select="handleDateSelect"
        />
      </div>
  
      <!-- View Selection -->
      <nav class="mb-6">
        <ul>
          <li v-for="item in views" :key="item.id">
            <button
              class="w-full text-left px-3 py-2 rounded-full mb-1"
              :class="view === item.id
                ? 'bg-blue-100 text-blue-700 font-medium'
                : 'hover:bg-gray-100'"
              @click="handleViewChange(item.id)"
            >
              {{ item.label }}
            </button>
          </li>
        </ul>
      </nav>
  
      <!-- Calendar Labels -->
      <div class="border-t border-gray-200 pt-4">
        <h3 class="text-sm font-medium text-gray-500 mb-2 px-3">
          My calendars
        </h3>
        <ul>
          <li
            v-for="calendar in calendars"
            :key="calendar.name"
            class="flex items-center px-3 py-2"
          >
            <span
              class="w-3 h-3 rounded-full mr-2"
              :style="{ backgroundColor: calendar.color }"
            ></span>
            <span>{{ calendar.name }}</span>
          </li>
        </ul>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { defineProps } from 'vue';
  import MiniCalendar from './MiniCalendar.vue';
  
  const props = defineProps<{
    view: string;
    selectedDate: Date;
    setView: (view: string) => void;
    setSelectedDate: (date: Date) => void;
    setCurrentDate: (date: Date) => void;
  }>();
  
  const views = [
    { id: 'day', label: 'Day' },
    { id: 'week', label: 'Week' },
    { id: 'month', label: 'Month' },
    { id: 'year', label: 'Year' }
  ];
  
  const calendars = [
    { name: 'Personal', color: '#4285F4' },
    { name: 'Work', color: '#0F9D58' },
    { name: 'Family', color: '#F4B400' }
  ];
  
  const handleViewChange = (newView: string) => {
    props.setView(newView);
  };
  
  const handleDateSelect = (date: Date) => {
    props.setSelectedDate(date);
    props.setCurrentDate(date);
  };
  </script>
  