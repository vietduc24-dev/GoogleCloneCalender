import { ref, computed } from 'vue';

export function useCalendarNavigation(calendarRef) {
  const currentDate = ref('');

  const formatDateHeader = (viewType, date, start, end) => {
    const formatDate = (date, type) => {
      const options = {
        day: { day: 'numeric', month: 'long', year: 'numeric' },
        month: { month: 'long', year: 'numeric' },
        week: { day: 'numeric', month: 'long', year: 'numeric' }
      };
      return new Intl.DateTimeFormat('vi-VN', options[type]).format(date);
    };

    const getWeekOfMonth = (date) => {
      const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
      return Math.ceil((date.getDate() + firstDay.getDay()) / 7);
    };

    switch (viewType) {
      case 'timeGridDay':
        return `Ngày ${formatDate(date, 'day')}`;
      case 'timeGridWeek': {
        const startDate = new Date(start);
        const endDate = new Date(end);
        if (startDate.getMonth() === endDate.getMonth()) {
          return `Tuần ${getWeekOfMonth(startDate)}, ${formatDate(startDate, 'month')}`;
        }
        return `${formatDate(startDate, 'week')} - ${formatDate(endDate, 'week')}`;
      }
      case 'dayGridMonth':
      default:
        return formatDate(date, 'month');
    }
  };

  const handleTodayClick = () => {
    if (!calendarRef.value) return;
    const api = calendarRef.value.getApi();
    api.today();
    currentDate.value = formatDateHeader(
      api.view.type,
      api.getDate(),
      api.view.currentStart,
      api.view.currentEnd
    );
  };

  const handlePrevClick = () => {
    if (!calendarRef.value) return;
    const api = calendarRef.value.getApi();
    api.prev();
    currentDate.value = formatDateHeader(
      api.view.type,
      api.getDate(),
      api.view.currentStart,
      api.view.currentEnd
    );
  };

  const handleNextClick = () => {
    if (!calendarRef.value) return;
    const api = calendarRef.value.getApi();
    api.next();
    currentDate.value = formatDateHeader(
      api.view.type,
      api.getDate(),
      api.view.currentStart,
      api.view.currentEnd
    );
  };

  const handleMiniCalendarSelect = (selectInfo) => {
    if (!calendarRef.value) return;
    const api = calendarRef.value.getApi();
    api.gotoDate(selectInfo.start);
    currentDate.value = formatDateHeader(
      api.view.type,
      api.getDate(),
      api.view.currentStart,
      api.view.currentEnd
    );
  };

  const handleSearch = (query) => {
    console.log('Search:', query);
    // Implement search functionality
  };

  return {
    currentDate,
    formatDateHeader,
    handleTodayClick,
    handlePrevClick,
    handleNextClick,
    handleMiniCalendarSelect,
    handleSearch
  };
} 