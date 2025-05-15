<template>
  <FullCalendar
    class="calendar-main h-full"
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

<style scoped>
.calendar-main {
  background-color: white;
}

:deep(.fc) {
  font-family: 'Google Sans', Roboto, Arial, sans-serif;
}

:deep(.fc-toolbar-title) {
  font-size: 1.5em !important;
  font-weight: 400;
  color: #3c4043;
}

:deep(.fc-button-primary) {
  background-color: transparent !important;
  border-color: #dadce0 !important;
  color: #3c4043 !important;
  text-transform: none !important;
  font-family: 'Google Sans', Roboto, Arial, sans-serif !important;
}

:deep(.fc-button-primary:hover) {
  background-color: #f1f3f4 !important;
  border-color: #dadce0 !important;
}

:deep(.fc-button-primary.fc-button-active) {
  background-color: #e8f0fe !important;
  color: #1a73e8 !important;
}

:deep(.fc-event) {
  cursor: pointer !important;
  padding: 2px 4px !important;
  border-radius: 3px !important;
  border: none !important;
}

:deep(.fc-event-main) {
  padding: 2px 4px !important;
}

:deep(.fc-event-main-frame) {
  background-color: inherit !important;
}

:deep(.fc-h-event),
:deep(.fc-daygrid-event),
:deep(.fc-timegrid-event) {
  background: inherit !important;
  border: none !important;
}

:deep(.fc-event:hover) {
  opacity: 0.9;
}

:deep(.fc-day-today) {
  background-color: #f8f9fa !important;
}

:deep(.fc-timegrid-slot) {
  height: 40px !important;
}

:deep(.fc-timegrid-axis) {
  padding-right: 8px;
  color: #70757a;
  font-size: 10px;
}

:deep(.fc-list-event:hover td) {
  background-color: #f1f3f4;
}

:deep(.fc-theme-standard td, .fc-theme-standard th) {
  border-color: #dadce0;
}

:deep(.fc-scrollgrid) {
  border: none !important;
}

:deep(.custom-reminder) {
  border-radius: 4px !important;
  padding: 2px 6px !important;
  margin: 1px 0 !important;
  font-size: 0.9em !important;
}

:deep(.custom-reminder .fc-event-main) {
  display: flex !important;
  align-items: center !important;
  gap: 4px !important;
}

:deep(.fc-timegrid-event.custom-reminder) {
  margin: 0 !important;
  border-radius: 4px !important;
}

:deep(.fc-daygrid-event.custom-reminder) {
  margin: 1px 0 !important;
  border-radius: 4px !important;
}
</style> 