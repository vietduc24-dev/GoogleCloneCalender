<template>
  <FullCalendar
    class="calendar-main h-full min-w-[76.8rem] flex-1"
    :options="calendarOptions"
    ref="fullCalendarRef"
  />
</template>

<script setup>
import { ref, computed } from 'vue';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';

const props = defineProps({
  events: {
    type: Array,
    required: true
  }
});

const emit = defineEmits(['select', 'eventClick', 'eventDrop', 'eventResize', 'datesSet']);

const fullCalendarRef = ref(null);

const calendarOptions = computed(() => ({
  plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin],
  initialView: 'timeGridWeek',
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
  },
  editable: true,
  selectable: true,
  selectMirror: true,
  dayMaxEvents: true,
  weekends: true,
  events: props.events,
  height: '100%',
  themeSystem: 'standard',
  // Event handlers
  select: (info) => emit('select', info),
  eventClick: (info) => emit('eventClick', info),
  eventDrop: (info) => emit('eventDrop', info),
  eventResize: (info) => emit('eventResize', info),
  datesSet: (info) => emit('datesSet', info),
  // Additional options
  nowIndicator: true,
  slotMinTime: '06:00:00',
  slotMaxTime: '22:00:00',
  slotDuration: '00:30:00',
  businessHours: {
    daysOfWeek: [1, 2, 3, 4, 5],
    startTime: '08:00',
    endTime: '18:00',
  },
  // Timezone settings
  timeZone: 'local',
  displayTimeZone: 'local',
  // Event Display
  eventDisplay: 'block',
  // Localization
  locale: 'vi',
  buttonText: {
    today: 'Hôm nay',
    month: 'Tháng',
    week: 'Tuần',
    day: 'Ngày',
    list: 'Danh sách'
  }
}));

// Expose calendar API methods
defineExpose({
  getApi: () => fullCalendarRef.value?.getApi()
});
</script>

