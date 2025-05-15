import { computed, ref } from 'vue';
import { useEventStore } from '../../stores/event';
import { useCalendarStore } from '../../stores/calendar';
import { useReminderStore } from '../../stores/reminder';
import { formatDate, formatDateForInput, utcToLocal, localToUtc } from '../../utils/fortmatdatetime';

// Debounce function
const debounce = (fn, delay) => {
  let timeoutId;
  return (...args) => {
    if (timeoutId) {
      clearTimeout(timeoutId);
    }
    timeoutId = setTimeout(() => {
      fn(...args);
    }, delay);
  };
};

export function useCalendarEvent(calendarApi) {
  const eventStore = useEventStore();
  const calendarStore = useCalendarStore();
  const reminderStore = useReminderStore();
  
  // Display state
  const showEvents = ref(true);
  const showReminders = ref(true);
  
  // Modal state
  const showEventModal = ref(false);
  const selectedEvent = ref(null);
  const selectedReminder = ref(null);
  const defaultDate = ref(null);
  const isEvent = ref(true);

  const events = computed(() => {
    const eventList = showEvents.value ? eventStore.getEvents : [];
    const reminderList = showReminders.value ? reminderStore.getReminders : [];
    
    const formattedEvents = Array.isArray(eventList) ? eventList.map(event => {
      const eventColor = event.color || '#4285F4';
      
      // Convert UTC times to local time for display
      const startLocal = utcToLocal(event.start_date);
      const endLocal = utcToLocal(event.end_date);
      
      return {
        id: `event_${event.id}`,
        title: event.title,
        start: startLocal,
        end: endLocal,
        allDay: event.all_day,
        backgroundColor: eventColor,
        borderColor: eventColor,
        textColor: '#FFFFFF',
        classNames: ['custom-event'],
        type: 'event',
        extendedProps: {
          description: event.description,
          location: event.location,
          color: eventColor,
          type: 'event'
        }
      };
    }) : [];

    const formattedReminders = Array.isArray(reminderList) ? reminderList.map(reminder => {
      const reminderColor = reminder.color || '#EA4335';
      const reminderTimeLocal = utcToLocal(reminder.reminder_time);
      
      return {
        id: `reminder_${reminder.id}`,
        title: `🔔 ${reminder.title}`,
        start: reminderTimeLocal,
        end: reminderTimeLocal,
        allDay: false,
        backgroundColor: reminderColor,
        borderColor: reminderColor,
        textColor: '#FFFFFF',
        classNames: ['custom-reminder'],
        type: 'reminder',
        display: 'block',
        extendedProps: {
          method: reminder.method,
          color: reminderColor,
          type: 'reminder'
        }
      };
    }) : [];

    return [...formattedEvents, ...formattedReminders];
  });

  const reminders = computed(() => {
    return reminderStore.getReminders;
  });

  const handleDateSelect = async (selectInfo) => {
    try {
      if (!calendarStore.getSelectedCalendar) {
        const defaultCalendar = await calendarStore.createCalendar({
          name: 'Lịch của tôi',
          color: '#4285F4',
          description: 'Lịch mặc định'
        });
        calendarStore.setSelectedCalendar(defaultCalendar);
      }

      defaultDate.value = {
        start: selectInfo.start,
        end: selectInfo.end,
        allDay: selectInfo.allDay
      };
      showEventModal.value = true;

      // Unselect the date range
      const calendarApi = selectInfo.view.calendar;
      calendarApi.unselect();
    } catch (error) {
      console.error('Error handling date select:', error);
      alert('Có lỗi xảy ra khi tạo sự kiện');
    }
  };

  const handleEventClick = async (clickInfo) => {
    const eventType = clickInfo.event.extendedProps.type;
    
    if (eventType === 'reminder') {
      const reminderId = parseInt(clickInfo.event.id.replace('reminder_', ''));
      const reminder = reminderStore.getReminders.find(r => r.id === reminderId);
      if (reminder) {
        selectedReminder.value = reminder;
        selectedEvent.value = {
          id: reminder.id,
          type: 'reminder',
          title: reminder.title,
          reminder_time: formatDateForInput(reminder.reminder_time),
          method: reminder.method,
          color: reminder.color || '#4285F4'
        };
      }
    } else {
      selectedReminder.value = null;
      selectedEvent.value = {
        id: parseInt(clickInfo.event.id.replace('event_', '')),
        type: 'event',
        title: clickInfo.event.title,
        start: formatDateForInput(clickInfo.event.start),
        end: formatDateForInput(clickInfo.event.end),
        allDay: clickInfo.event.allDay,
        description: clickInfo.event.extendedProps.description,
        location: clickInfo.event.extendedProps.location,
        color: clickInfo.event.backgroundColor || clickInfo.event.extendedProps.color || '#4285F4'
      };
    }
    showEventModal.value = true;
  };

  const handleReminderClick = (reminder) => {
    selectedReminder.value = {
      id: reminder.id,
      title: reminder.title,
      reminder_time: reminder.reminder_time,
      method: reminder.method,
      color: reminder.color || '#4285F4'
    };
    showEventModal.value = true;
  };

  const handleEventDrop = async (dropInfo) => {
    try {
      // Extract event ID from the combined ID (event_1 or reminder_1)
      const id = parseInt(dropInfo.event.id.split('_')[1]);
      const type = dropInfo.event.id.split('_')[0];
      
      if (type === 'reminder') {
        const reminderData = {
          title: dropInfo.event.title.replace('🔔 ', ''),
          reminder_time: formatDate(localToUtc(dropInfo.event.start)),
          method: dropInfo.event.extendedProps.method,
          color: dropInfo.event.backgroundColor || '#4285F4'
        };
        await reminderStore.updateReminder(id, reminderData);
        await fetchReminders();
      } else {
        const eventData = {
          start_date: formatDate(localToUtc(dropInfo.event.start)),
          end_date: formatDate(localToUtc(dropInfo.event.end)),
          all_day: dropInfo.event.allDay,
          title: dropInfo.event.title,
          description: dropInfo.event.extendedProps.description,
          location: dropInfo.event.extendedProps.location,
          color: dropInfo.event.backgroundColor || dropInfo.event.extendedProps.color || '#4285F4'
        };
        await eventStore.updateEvent(id, eventData);
        await fetchEvents();
      }
    } catch (error) {
      console.error('Error updating event:', error);
      dropInfo.revert();
      alert('Có lỗi xảy ra khi cập nhật ' + (type === 'reminder' ? 'lời nhắc' : 'sự kiện'));
    }
  };

  const handleEventResize = async (resizeInfo) => {
    try {
      const id = parseInt(resizeInfo.event.id.split('_')[1]);
      const eventData = {
        start_date: formatDate(localToUtc(resizeInfo.event.start)),
        end_date: formatDate(localToUtc(resizeInfo.event.end)),
        all_day: resizeInfo.event.allDay,
        title: resizeInfo.event.title,
        description: resizeInfo.event.extendedProps.description,
        location: resizeInfo.event.extendedProps.location,
        color: resizeInfo.event.backgroundColor || resizeInfo.event.extendedProps.color || '#4285F4'
      };
      await eventStore.updateEvent(id, eventData);
      await fetchEvents();
    } catch (error) {
      console.error('Error updating event:', error);
      resizeInfo.revert();
      alert('Có lỗi xảy ra khi cập nhật sự kiện');
    }
  };

  // Debounced versions of the handlers
  const debouncedEventDrop = debounce(handleEventDrop, 500);
  const debouncedEventResize = debounce(handleEventResize, 500);

  const handleModalSubmit = async (formData) => {
    try {
      // Handle delete operation
      if (formData.action === 'delete') {
        if (formData.type === 'event' && selectedEvent.value) {
          await eventStore.deleteEvent(selectedEvent.value.id);
          await fetchEvents();
        } else if (formData.type === 'reminder' && selectedReminder.value) {
          await reminderStore.deleteReminder(selectedReminder.value.id);
          await fetchReminders();
        }
        closeEventModal();
        return;
      }

      // Handle create/update operations
      if (formData.type === 'event') {
        if (!calendarStore.getSelectedCalendar) {
          throw new Error('Vui lòng chọn lịch trước khi tạo sự kiện');
        }

        const eventData = {
          calendar_id: calendarStore.getSelectedCalendar.id,
          title: formData.data.title,
          description: formData.data.description || '',
          location: formData.data.location || '',
          start_date: formatDate(localToUtc(formData.data.start)),
          end_date: formatDate(localToUtc(formData.data.end)),
          all_day: formData.data.all_day || false,
          color: formData.data.color || '#4285F4'
        };

        if (selectedEvent.value) {
          await eventStore.updateEvent(selectedEvent.value.id, eventData);
        } else {
          await eventStore.createEvent(eventData);
        }
      } else {
        const reminderData = {
          title: formData.data.title,
          reminder_time: formatDate(localToUtc(formData.data.reminder_time)),
          method: formData.data.method,
          color: formData.data.color || '#4285F4'
        };

        if (selectedReminder.value) {
          await reminderStore.updateReminder(selectedReminder.value.id, reminderData);
        } else {
          await reminderStore.createReminder(reminderData);
        }
      }

      closeEventModal();
      await fetchEvents();
      await fetchReminders();
    } catch (error) {
      console.error('Error processing operation:', error);
      let errorMessage = 'Có lỗi xảy ra';
      
      if (error.response?.data?.errors) {
        const errors = Object.values(error.response.data.errors).flat();
        errorMessage = errors.join('\n');
      } else if (error.response?.data?.message) {
        errorMessage = error.response.data.message;
      } else if (error.message) {
        errorMessage = error.message;
      }
      
      alert(errorMessage);
    }
  };

  const closeEventModal = () => {
    showEventModal.value = false;
    selectedEvent.value = null;
    selectedReminder.value = null;
    defaultDate.value = null;
  };

  const fetchEvents = async () => {
    if (!calendarStore.getSelectedCalendar) return;
    if (!calendarApi.value) return;

    const view = calendarApi.value.getApi().view;
    const start = view.activeStart;
    const end = view.activeEnd;

    try {
      await eventStore.fetchEvents(
        calendarStore.getSelectedCalendar.id,
        start.toISOString(),
        end.toISOString()
      );
    } catch (error) {
      console.error('Error fetching events:', error);
    }
  };

  const fetchReminders = async () => {
    try {
      await reminderStore.fetchReminders();
    } catch (error) {
      console.error('Error fetching reminders:', error);
    }
  };

  const handleCreateClick = () => {
    if (!calendarApi.value) return;
    
    const now = new Date();
    const endTime = new Date(now.getTime() + 60 * 60 * 1000); // 1 hour later
    
    defaultDate.value = {
      start: now,
      end: endTime,
      allDay: false
    };
    showEventModal.value = true;
  };

  const handleDelete = async ({ type }) => {
    try {
      if (type === 'event' && selectedEvent.value) {
        await eventStore.deleteEvent(selectedEvent.value.id);
        await fetchEvents();
      } else if (type === 'reminder' && selectedReminder.value) {
        await reminderStore.deleteReminder(selectedReminder.value.id);
        await fetchReminders();
      }
      closeEventModal();
    } catch (error) {
      console.error('Error deleting:', error);
      alert('Có lỗi xảy ra khi xóa ' + (type === 'event' ? 'sự kiện' : 'lời nhắc'));
    }
  };

  const handleDisplayToggle = ({ type, value }) => {
    if (type === 'events') {
      showEvents.value = value;
    } else if (type === 'reminders') {
      showReminders.value = value;
    }
  };

  // Initialize
  fetchReminders();

  return {
    events,
    reminders,
    showEvents,
    showReminders,
    showEventModal,
    selectedEvent,
    selectedReminder,
    defaultDate,
    handleDateSelect,
    handleEventClick,
    handleReminderClick,
    handleEventDrop: debouncedEventDrop,
    handleEventResize: debouncedEventResize,
    handleModalSubmit,
    closeEventModal,
    fetchEvents,
    fetchReminders,
    handleCreateClick,
    handleDelete,
    handleDisplayToggle
  };
} 