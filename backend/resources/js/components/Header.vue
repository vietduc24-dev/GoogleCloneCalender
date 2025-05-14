<template>
    <header class="flex items-center justify-between px-4 py-2 border-b border-gray-200 bg-white">
      <!-- Left Section -->
      <div class="flex items-center">
        <button class="md:hidden p-2 mr-2">
          <MenuIcon :size="20" />
        </button>
        <div class="flex items-center">
          <span class="text-xl font-semibold text-blue-600 mr-2">Calendar</span>
        </div>
      </div>
  
      <!-- Center Navigation -->
      <div class="flex items-center space-x-2">
        <button @click="goToToday" class="px-4 py-1 text-sm border border-gray-300 rounded hover:bg-gray-100">
          Today
        </button>
        <button @click="goToPrevious" class="p-1 hover:bg-gray-100 rounded">
          <ChevronLeftIcon :size="20" />
        </button>
        <button @click="goToNext" class="p-1 hover:bg-gray-100 rounded">
          <ChevronRightIcon :size="20" />
        </button>
        <h2 class="text-lg font-medium hidden sm:block">{{ formatDateRange() }}</h2>
      </div>
  
      <!-- Search -->
      <div class="flex items-center">
        <div class="relative hidden md:block">
          <input
            type="text"
            placeholder="Search"
            class="pl-8 pr-4 py-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500"
          />
          <SearchIcon
            :size="16"
            class="absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-400"
          />
        </div>
      </div>
    </header>
  </template>
  
  <script setup lang="ts">
  import { ref } from 'vue';
  import { MenuIcon, ChevronLeftIcon, ChevronRightIcon, SearchIcon } from 'lucide-vue-next';
  
  const props = defineProps<{
    currentDate: Date;
    view: 'day' | 'week' | 'month';
  }>();
  
  const emit = defineEmits<{
    (e: 'update:currentDate', value: Date): void;
  }>();
  
  // Navigation handlers
  const goToToday = () => {
    emit('update:currentDate', new Date());
  };
  
  const goToPrevious = () => {
    const newDate = new Date(props.currentDate);
    if (props.view === 'day') {
      newDate.setDate(newDate.getDate() - 1);
    } else if (props.view === 'week') {
      newDate.setDate(newDate.getDate() - 7);
    } else if (props.view === 'month') {
      newDate.setMonth(newDate.getMonth() - 1);
    }
    emit('update:currentDate', newDate);
  };
  
  const goToNext = () => {
    const newDate = new Date(props.currentDate);
    if (props.view === 'day') {
      newDate.setDate(newDate.getDate() + 1);
    } else if (props.view === 'week') {
      newDate.setDate(newDate.getDate() + 7);
    } else if (props.view === 'month') {
      newDate.setMonth(newDate.getMonth() + 1);
    }
    emit('update:currentDate', newDate);
  };
  
  const formatDateRange = () => {
    return props.currentDate.toLocaleDateString('en-US', {
      month: 'long',
      year: 'numeric',
    });
  };
  </script>
  