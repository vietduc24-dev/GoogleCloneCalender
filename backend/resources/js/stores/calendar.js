import { defineStore } from 'pinia';
import { api } from '../utils/axios';

export const useCalendarStore = defineStore('calendar', {
    state: () => ({
        calendars: [],
        selectedCalendar: null,
        loading: false,
        error: null
    }),

    getters: {
        getCalendars: (state) => state.calendars || [],
        getSelectedCalendar: (state) => state.selectedCalendar,
        isLoading: (state) => state.loading,
        getError: (state) => state.error
    },

    actions: {
        async fetchCalendars() {
            this.loading = true;
            try {
                const response = await api.get('/calendars');
                if (response.data.status === 'success') {
                    this.calendars = response.data.data;
                    return this.calendars;
                }
                throw new Error(response.data.message || 'Không thể tải danh sách lịch');
            } catch (error) {
                this.error = error.response?.data?.message || 'Không thể tải danh sách lịch';
                this.calendars = [];
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async createCalendar(calendarData) {
            this.loading = true;
            try {
                const response = await api.post('/calendars', {
                    name: calendarData.name,
                    color: calendarData.color,
                    description: calendarData.description
                });
                
                if (response.data.status === 'success') {
                    const newCalendar = response.data.data;
                    this.calendars.push(newCalendar);
                    return newCalendar;
                }
                throw new Error(response.data.message || 'Không thể tạo lịch');
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
                const response = await api.put(`/calendars/${calendarId}`, {
                    name: calendarData.name,
                    color: calendarData.color,
                    description: calendarData.description
                });

                if (response.data.status === 'success') {
                    const updatedCalendar = response.data.data;
                    const index = this.calendars.findIndex(c => c.id === calendarId);
                    if (index !== -1) {
                        this.calendars[index] = updatedCalendar;
                    }
                    return updatedCalendar;
                }
                throw new Error(response.data.message || 'Không thể cập nhật lịch');
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
                const response = await api.delete(`/calendars/${calendarId}`);
                if (response.data.status === 'success') {
                    this.calendars = this.calendars.filter(c => c.id !== calendarId);
                    if (this.selectedCalendar?.id === calendarId) {
                        this.selectedCalendar = null;
                    }
                    return true;
                }
                throw new Error(response.data.message || 'Không thể xóa lịch');
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