import { onMounted, watch } from 'vue';
import { useCalendarStore } from '../../stores/calendar';

export function useCalendarSetup(fetchEvents) {
  const calendarStore = useCalendarStore();

  const setupCalendar = async () => {
    try {
      await calendarStore.fetchCalendars();
      const calendars = calendarStore.getCalendars;
      
      if (calendars.length === 0) {
        const defaultCalendar = await calendarStore.createCalendar({
          name: 'Lịch của tôi',
          color: '#4285F4',
          description: 'Lịch mặc định'
        });
        calendarStore.setSelectedCalendar(defaultCalendar);
      } else {
        calendarStore.setSelectedCalendar(calendars[0]);
      }
      
      await fetchEvents();
    } catch (error) {
      console.error('Error setting up calendar:', error);
    }
  };

  // Watch for changes that should trigger events refresh
  watch(() => calendarStore.getSelectedCalendar, (newCalendar) => {
    if (newCalendar) {
      fetchEvents();
    }
  });

  onMounted(setupCalendar);

  return {
    toggleCalendar: (calendar) => {
      calendarStore.setSelectedCalendar(calendar);
    }
  };
} 