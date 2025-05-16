import { defineStore } from 'pinia';
import { api } from '../utils/axios';

export const useEventStore = defineStore('event', {
    state: () => ({
        events: [],
        selectedEvent: null,
        loading: false,
        error: null
    }),

    getters: {
        getEvents: (state) => state.events || [],
        getSelectedEvent: (state) => state.selectedEvent,
        isLoading: (state) => state.loading,
        getError: (state) => state.error
    },

    actions: {
        async fetchEvents(calendarId, start, end) {
            this.loading = true;
            try {
                const response = await api.get('/events', {
                    params: { 
                        calendar_id: calendarId,
                        start: start,
                        end: end
                    }
                });

                if (response.data.status === 'success') {
                    this.events = response.data.data;
                    return this.events;
                }
                throw new Error(response.data.message || 'Không thể tải sự kiện');
            } catch (error) {
                console.error('Error fetching events:', error);
                this.error = error.response?.data?.message || 'Không thể tải sự kiện';
                this.events = [];
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async createEvent(eventData) {
            this.loading = true;
            try {
                const formattedData = {
                    calendar_id: eventData.calendar_id,
                    title: eventData.title,
                    description: eventData.description || '',
                    location: eventData.location || '',
                    start_date: eventData.start_date,
                    end_date: eventData.end_date,
                    all_day: Boolean(eventData.all_day),
                    color: eventData.color
                };

                console.log('Sending event data:', formattedData); // Debug log

                const response = await api.post('/events', formattedData);
                
                if (response.data.status === 'success') {
                    const newEvent = response.data.data;
                    this.events = [...this.events, newEvent];
                    return newEvent;
                }
                throw new Error(response.data.message || 'Không thể tạo sự kiện');
            } catch (error) {
                console.error('Error creating event:', error);
                if (error.response?.data?.errors) {
                    console.error('Validation errors:', error.response.data.errors);
                }
                this.error = error.response?.data?.message || 'Không thể tạo sự kiện';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async updateEvent(eventId, eventData) {
            this.loading = true;
            try {
                const formattedData = {
                    title: eventData.title,
                    description: eventData.description,
                    location: eventData.location,
                    start_date: eventData.start_date,
                    end_date: eventData.end_date,
                    all_day: eventData.all_day || false,
                    color: eventData.color
                };

                const response = await api.put(`/events/${eventId}`, formattedData);
                
                if (response.data.status === 'success') {
                    const updatedEvent = response.data.data;
                    const index = this.events.findIndex(e => e.id === eventId);
                    if (index !== -1) {
                        this.events[index] = updatedEvent;
                    }
                    return updatedEvent;
                }
                throw new Error(response.data.message || 'Không thể cập nhật sự kiện');
            } catch (error) {
                console.error('Error updating event:', error);
                this.error = error.response?.data?.message || 'Không thể cập nhật sự kiện';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async deleteEvent(eventId) {
            this.loading = true;
            try {
                const response = await api.delete(`/events/${eventId}`);
                if (response.data.status === 'success') {
                    this.events = this.events.filter(e => e.id !== eventId);
                    return true;
                }
                throw new Error(response.data.message || 'Không thể xóa sự kiện');
            } catch (error) {
                console.error('Error deleting event:', error);
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
