import { defineStore } from 'pinia';
import axios from 'axios';

export const useEventStore = defineStore('event', {
    state: () => ({
        events: [],
        selectedEvent: null,
        loading: false,
        error: null
    }),

    getters: {
        getEvents: (state) => state.events,
        getSelectedEvent: (state) => state.selectedEvent,
        isLoading: (state) => state.loading,
        getError: (state) => state.error
    },

    actions: {
        async fetchEvents(calendarId, start, end) {
            this.loading = true;
            try {
                const response = await axios.get('/api/events', {
                    params: { calendar_id: calendarId, start, end }
                });
                this.events = response.data;
                return response.data;
            } catch (error) {
                this.error = error.response?.data?.message || 'Không thể tải sự kiện';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async createEvent(eventData) {
            this.loading = true;
            try {
                const response = await axios.post('/api/events', eventData);
                this.events.push(response.data);
                return response.data;
            } catch (error) {
                this.error = error.response?.data?.message || 'Không thể tạo sự kiện';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async updateEvent(eventId, eventData) {
            this.loading = true;
            try {
                const response = await axios.put(`/api/events/${eventId}`, eventData);
                const index = this.events.findIndex(e => e.id === eventId);
                if (index !== -1) {
                    this.events[index] = response.data;
                }
                return response.data;
            } catch (error) {
                this.error = error.response?.data?.message || 'Không thể cập nhật sự kiện';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async deleteEvent(eventId) {
            this.loading = true;
            try {
                await axios.delete(`/api/events/${eventId}`);
                this.events = this.events.filter(e => e.id !== eventId);
            } catch (error) {
                this.error = error.response?.data?.message || 'Không thể xóa sự kiện';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        setSelectedEvent(event) {
            this.selectedEvent = event;
        },

        clearSelectedEvent() {
            this.selectedEvent = null;
        },

        clearError() {
            this.error = null;
        }
    }
});
