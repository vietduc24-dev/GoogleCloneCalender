<template>
  <div class="flex flex-col h-screen bg-gray-50">
    <!-- Loading Overlay -->
    <div v-if="calendarStore.isLoading || eventStore.isLoading" 
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
    </div>

    <!-- Error Toast -->
    <div v-if="calendarStore.getError || eventStore.getError"
         class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50">
      <span class="block sm:inline">{{ calendarStore.getError || eventStore.getError }}</span>
      <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="clearErrors">
        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
          <title>Close</title>
          <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
        </svg>
      </span>
    </div>

    <!-- Header -->
    <CalendarHeader
      :currentDate="currentDate"
      :view="calendarView"
      @today-click="handleTodayClick"
      @prev-click="handlePrevClick"
      @next-click="handleNextClick"
      @view-change="handleViewChange"
    />

    <!-- Main Content Area -->
    <div class="flex flex-1 overflow-hidden">
      <!-- Left Sidebar -->
      <CalendarSidebar
        :calendars="calendarStore.getCalendars"
        :selectedCalendar="calendarStore.getSelectedCalendar"
        :events="events"
        :showEvents="showEvents"
        :showReminders="showReminders"
        @create-click="handleCreateClick"
        @toggle-calendar="toggleCalendar"
        @toggle-display="handleDisplayToggle"
        @mini-calendar-select="handleMiniCalendarSelect"
      />

      <!-- Calendar Container -->
      <div class="flex-1 overflow-auto">
        <FullCalendar
          ref="calendarRef"
          :options="calendarOptions"
        />
      </div>
    </div>

    <!-- Event Modal -->
    <EventModal
      :show="showEventModal"
      :event="selectedEvent"
      :defaultDate="defaultDate"
      @close="closeEventModal"
      @submit="handleModalSubmit"
      @delete="handleEventDelete"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';
import { useCalendarStore } from '../../stores/calendar';
import { useEventStore } from '../../stores/event';
import { useCalendarEvent } from '../../composables/calendar/useCalendarEvent';
import { useCalendarNavigation } from '../../composables/calendar/useCalendarNavigation';
import { useCalendarSetup } from '../../composables/calendar/useCalendarSetup';
import CalendarSidebar from '../../components/calendar/CalendarSidebar.vue';
import CalendarHeader from '../../components/calendar/CalendarHeader.vue';
import EventModal from '../../components/calendar/EventModal.vue';

const calendarRef = ref(null);
const calendarStore = useCalendarStore();
const eventStore = useEventStore();
const calendarView = ref('dayGridMonth');

const {
  events,
  showEvents,
  showReminders,
  showEventModal,
  selectedEvent,
  defaultDate,
  handleDateSelect,
  handleEventClick,
  handleEventDrop,
  handleEventResize,
  handleModalSubmit,
  closeEventModal,
  fetchEvents,
  handleCreateClick,
  handleEventDelete,
  handleDisplayToggle
} = useCalendarEvent(calendarRef);

const {
  currentDate,
  formatDateHeader,
  handleTodayClick,
  handlePrevClick,
  handleNextClick,
  handleMiniCalendarSelect
} = useCalendarNavigation(calendarRef);

const { toggleCalendar } = useCalendarSetup(fetchEvents);

const handleViewChange = (newView) => {
  calendarView.value = newView;
  if (calendarRef.value) {
    const api = calendarRef.value.getApi();
    api.changeView(newView);
    currentDate.value = formatDateHeader(
      api.view.type,
      api.getDate(),
      api.view.currentStart,
      api.view.currentEnd
    );
  }
};

const clearErrors = () => {
  calendarStore.clearError();
  eventStore.clearError();
};

const calendarOptions = computed(() => ({
  plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin],
  initialView: calendarView.value,
  headerToolbar: false,
  events: events.value,
  editable: true,
  selectable: true,
  selectMirror: true,
  dayMaxEvents: true,
  weekends: true,
  select: handleDateSelect,
  eventClick: handleEventClick,
  eventDrop: handleEventDrop,
  eventResize: handleEventResize,
  datesSet: (arg) => {
    fetchEvents();
    if (calendarRef.value) {
      const api = calendarRef.value.getApi();
      currentDate.value = formatDateHeader(
        api.view.type,
        api.getDate(),
        api.view.currentStart,
        api.view.currentEnd
      );
    }
  },
  height: '100%',
  nowIndicator: true,
  slotMinTime: '06:00:00',
  slotMaxTime: '22:00:00',
  slotDuration: '00:30:00',
  allDaySlot: true,
  timeZone: 'local',
  displayTimeZone: 'local',
  eventTimeFormat: {
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
    timeZone: 'local'
  }
}));

onMounted(() => {
  if (calendarRef.value) {
    const api = calendarRef.value.getApi();
    api.setOption('locale', 'vi');
    currentDate.value = formatDateHeader(
      api.view.type,
      api.getDate(),
      api.view.currentStart,
      api.view.currentEnd
    );
  }
});
</script>

<style>
/* Reset some base styles */
.calendar-main {
  background-color: white;
}

/* FullCalendar Custom Styles */
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
  cursor: pointer;
  padding: 2px 4px;
  border-radius: 3px;
  border: none;
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

:deep(.fc-now-indicator) {
  border-color: #ea4335;
}

:deep(.fc-timegrid-now-indicator-line) {
  border-color: #ea4335;
}

:deep(.fc-timegrid-now-indicator-arrow) {
  border-color: #ea4335;
  background-color: #ea4335;
}
</style>
