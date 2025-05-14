import { defineStore } from 'pinia';
import axios from 'axios';

// Sử dụng cùng cấu hình axios từ auth store
axios.defaults.baseURL = 'http://127.0.0.1:8000';
axios.defaults.headers.common['Content-Type'] = 'application/json';
axios.defaults.headers.common['Accept'] = 'application/json';

export const useCalendarStore = defineStore('calendar', {
    state: () => ({
        calendars: [],
        selectedCalendar: null,
        loading: false,
        error: null
    }),

    getters: {
        getCalendars: (state) => state.calendars,
        getSelectedCalendar: (state) => state.selectedCalendar,
        isLoading: (state) => state.loading,
        getError: (state) => state.error
    },

    actions: {
        async fetchCalendars() {
            this.loading = true;
            try {
                const response = await axios.get('/api/calendars');
                this.calendars = response.data;
                return response.data;
            } catch (error) {
                this.error = error.response?.data?.message || 'Không thể tải danh sách lịch';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async createCalendar(calendarData) {
            this.loading = true;
            try {
                const response = await axios.post('/api/calendars', {
                    calendar_name: calendarData.name,
                    color: calendarData.color,
                    description: calendarData.description
                });
                this.calendars.push(response.data);
                return response.data;
            } catch (error) {
                this.error = error.response?.data?.message || 'Không thể tạo lịch';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async updateCalendar(calendarId, calendarData) {
            this.loading = true;
            try {
                const response = await axios.put(`/api/calendars/${calendarId}`, {
                    calendar_name: calendarData.name,
                    color: calendarData.color,
                    description: calendarData.description
                });
                const index = this.calendars.findIndex(c => c.id === calendarId);
                if (index !== -1) {
                    this.calendars[index] = response.data;
                }
                return response.data;
            } catch (error) {
                this.error = error.response?.data?.message || 'Không thể cập nhật lịch';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async deleteCalendar(calendarId) {
            this.loading = true;
            try {
                await axios.delete(`/api/calendars/${calendarId}`);
                this.calendars = this.calendars.filter(c => c.id !== calendarId);
                if (this.selectedCalendar?.id === calendarId) {
                    this.selectedCalendar = null;
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Không thể xóa lịch';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        setSelectedCalendar(calendar) {
            this.selectedCalendar = calendar;
        },

        clearSelectedCalendar() {
            this.selectedCalendar = null;
        },

        clearError() {
            this.error = null;
        }
    }
}); 