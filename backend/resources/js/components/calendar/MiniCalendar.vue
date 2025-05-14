<template>
    <div class="mini-calendar">
      <!-- Header: Month & Navigation -->
      <div class="flex justify-between items-center mb-2">
        <button @click="goToPreviousMonth" class="text-gray-600 p-1 hover:bg-gray-100 rounded">
          &lt;
        </button>
        <div class="text-sm font-medium">
          {{ currentMonth.toLocaleDateString('en-US', { month: 'long', year: 'numeric' }) }}
        </div>
        <button @click="goToNextMonth" class="text-gray-600 p-1 hover:bg-gray-100 rounded">
          &gt;
        </button>
      </div>
  
      <!-- Weekdays -->
      <div class="grid grid-cols-7 gap-1 text-xs text-center font-medium text-gray-500">
        <div v-for="(day, i) in ['S', 'M', 'T', 'W', 'T', 'F', 'S']" :key="i">{{ day }}</div>
      </div>
  
      <!-- Dates -->
      <div class="grid grid-cols-7 gap-1 mt-1">
        <template v-for="(cell, index) in calendarCells" :key="index">
          <div
            v-if="cell === null"
            class="h-6 w-6"
          ></div>
          <div
            v-else
            :class="[
              'flex items-center justify-center h-6 w-6 rounded-full cursor-pointer text-sm',
              isSelected(cell) ? 'bg-blue-500 text-white' : '',
              isToday(cell) && !isSelected(cell) ? 'border border-blue-500 font-medium' : '',
              !isSelected(cell) && !isToday(cell) ? 'hover:bg-gray-100' : ''
            ]"
            @click="() => emit('select', cell)"
          >
            {{ cell.getDate() }}
          </div>
        </template>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref, computed } from 'vue';
  
  const props = defineProps<{
    selectedDate: Date;
  }>();
  
  const emit = defineEmits<{
    (e: 'select', value: Date): void;
  }>();
  
  const currentMonth = ref(new Date());
  
  const getDaysInMonth = (year: number, month: number) => {
    return new Date(year, month + 1, 0).getDate();
  };
  
  const getFirstDayOfMonth = (year: number, month: number) => {
    return new Date(year, month, 1).getDay();
  };
  
  const goToPreviousMonth = () => {
    const newMonth = new Date(currentMonth.value);
    newMonth.setMonth(newMonth.getMonth() - 1);
    currentMonth.value = newMonth;
  };
  
  const goToNextMonth = () => {
    const newMonth = new Date(currentMonth.value);
    newMonth.setMonth(newMonth.getMonth() + 1);
    currentMonth.value = newMonth;
  };
  
  const isToday = (date: Date) => {
    const today = new Date();
    return (
      date.getDate() === today.getDate() &&
      date.getMonth() === today.getMonth() &&
      date.getFullYear() === today.getFullYear()
    );
  };
  
  const isSelected = (date: Date) => {
    return (
      date.getDate() === props.selectedDate.getDate() &&
      date.getMonth() === props.selectedDate.getMonth() &&
      date.getFullYear() === props.selectedDate.getFullYear()
    );
  };
  
  const calendarCells = computed(() => {
    const year = currentMonth.value.getFullYear();
    const month = currentMonth.value.getMonth();
    const daysInMonth = getDaysInMonth(year, month);
    const firstDay = getFirstDayOfMonth(year, month);
  
    const cells: (Date | null)[] = [];
  
    // Empty cells before the first day of month
    for (let i = 0; i < firstDay; i++) {
      cells.push(null);
    }
  
    // Days of the month
    for (let day = 1; day <= daysInMonth; day++) {
      cells.push(new Date(year, month, day));
    }
  
    return cells;
  });
  </script>
  