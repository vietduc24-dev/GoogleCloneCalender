<template>
  <div class="mini-calendar bg-white rounded-lg p-4 w-full">
    <div class="flex items-center justify-between mb-4">
      <button 
        @click="prevMonth" 
        class="text-gray-600 hover:text-gray-800 p-1"
      >
        <span class="material-symbols-outlined">chevron_left</span>
      </button>
      <h3 class="text-sm font-medium text-gray-700 min-w-[120px] text-center">{{ currentMonthYear }}</h3>
      <button 
        @click="nextMonth" 
        class="text-gray-600 hover:text-gray-800 p-1"
      >
        <span class="material-symbols-outlined">chevron_right</span>
      </button>
    </div>

    <!-- Weekday headers -->
    <div class="grid grid-cols-7 mb-2">
      <div 
        v-for="day in weekDays" 
        :key="day" 
        class="text-center text-xs text-gray-500 font-medium py-1"
      >
        {{ day }}
      </div>
    </div>

    <!-- Calendar days -->
    <div class="grid grid-cols-7 gap-1">
      <div
        v-for="{ date, isCurrentMonth, isToday } in calendarDays"
        :key="date.toISOString()"
        class="aspect-square"
      >
        <button
          v-if="isCurrentMonth"
          @click="selectDate(date)"
          :class="[
            'w-full h-full rounded-full text-sm leading-none flex items-center justify-center transition-colors min-w-[2.4rem] min-h-[2.4rem]',
            isToday 
              ? 'bg-blue-600 text-white hover:bg-blue-700' 
              : selectedDate && date.toDateString() === selectedDate.toDateString()
                ? 'bg-blue-50 text-blue-600 hover:bg-blue-100'
                : 'text-gray-700 hover:bg-gray-100'
          ]"
        >
          {{ date.getDate() }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const props = defineProps({
  onSelect: {
    type: Function,
    required: true
  }
});

const emit = defineEmits(['dateSelect']);
const currentDate = ref(new Date());
const selectedDate = ref(null);
const weekDays = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];

const currentMonthYear = computed(() => {
  return new Intl.DateTimeFormat('vi-VN', {
    month: 'long',
    year: 'numeric'
  }).format(currentDate.value);
});

const calendarDays = computed(() => {
  const year = currentDate.value.getFullYear();
  const month = currentDate.value.getMonth();
  
  // First day of the month
  const firstDay = new Date(year, month, 1);
  // Last day of the month
  const lastDay = new Date(year, month + 1, 0);
  
  // Get the first Sunday before the first day of the month
  const start = new Date(firstDay);
  start.setDate(start.getDate() - start.getDay());
  
  const days = [];
  const today = new Date();
  
  // Generate 6 weeks of dates
  for (let i = 0; i < 42; i++) {
    const date = new Date(start);
    date.setDate(start.getDate() + i);
    
    days.push({
      date,
      isCurrentMonth: date.getMonth() === month,
      isToday: date.toDateString() === today.toDateString()
    });
  }
  
  return days;
});

const prevMonth = () => {
  const newDate = new Date(currentDate.value);
  newDate.setMonth(newDate.getMonth() - 1);
  currentDate.value = newDate;
};

const nextMonth = () => {
  const newDate = new Date(currentDate.value);
  newDate.setMonth(newDate.getMonth() + 1);
  currentDate.value = newDate;
};

const selectDate = (date) => {
  selectedDate.value = date;
  const start = new Date(date);
  start.setHours(0, 0, 0, 0);
  
  const end = new Date(date);
  end.setHours(23, 59, 59, 999);
  
  props.onSelect({ start, end });
};

const handleSelect = (selectInfo) => {
  selectedDate.value = selectInfo.start;
  props.onSelect(selectInfo);
};

const handleUnselect = () => {
  selectedDate.value = null;
};

const handleDayCellMount = (arg) => {
  // Thêm xử lý nếu cần
};

onMounted(() => {
  // Initialize with current date
  currentDate.value = new Date();
});
</script> 