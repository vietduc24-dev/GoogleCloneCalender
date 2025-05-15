import { defineStore } from 'pinia';
import { api } from '../utils/axios';

export const useReminderStore = defineStore('reminder', {
    state: () => ({
        reminders: [],
        selectedReminder: null,
        loading: false,
        error: null
    }),

    getters: {
        getReminders: (state) => state.reminders || [],
        getSelectedReminder: (state) => state.selectedReminder,
        isLoading: (state) => state.loading,
        getError: (state) => state.error
    },

    actions: {
        async fetchReminders() {
            this.loading = true;
            try {
                const response = await api.get('/reminders');
                if (response.data.status === 'success') {
                    this.reminders = response.data.data;
                    return this.reminders;
                }
                throw new Error(response.data.message || 'Không thể tải danh sách lời nhắc');
            } catch (error) {
                console.error('Error fetching reminders:', error);
                this.error = error.response?.data?.message || 'Không thể tải danh sách lời nhắc';
                this.reminders = [];
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async createReminder(reminderData) {
            this.loading = true;
            try {
                const formattedData = {
                    title: reminderData.title,
                    reminder_time: reminderData.reminder_time,
                    method: reminderData.method || 'notification',
                    color: reminderData.color || '#EA4335'
                };

                const response = await api.post('/reminders', formattedData);
                
                if (response.data.status === 'success') {
                    const newReminder = response.data.data;
                    this.reminders = [...this.reminders, newReminder];
                    return newReminder;
                }
                throw new Error(response.data.message || 'Không thể tạo lời nhắc');
            } catch (error) {
                console.error('Error creating reminder:', error);
                this.error = error.response?.data?.message || 'Không thể tạo lời nhắc';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async updateReminder(reminderId, reminderData) {
            this.loading = true;
            try {
                const formattedData = {
                    title: reminderData.title,
                    reminder_time: reminderData.reminder_time,
                    method: reminderData.method,
                    color: reminderData.color || '#4285F4'
                };

                const response = await api.put(`/reminders/${reminderId}`, formattedData);
                
                if (response.data.status === 'success') {
                    const updatedReminder = response.data.data;
                    const index = this.reminders.findIndex(r => r.id === reminderId);
                    if (index !== -1) {
                        this.reminders[index] = updatedReminder;
                    }
                    return updatedReminder;
                }
                throw new Error(response.data.message || 'Không thể cập nhật lời nhắc');
            } catch (error) {
                console.error('Error updating reminder:', error);
                this.error = error.response?.data?.message || 'Không thể cập nhật lời nhắc';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async deleteReminder(reminderId) {
            this.loading = true;
            try {
                const response = await api.delete(`/reminders/${reminderId}`);
                if (response.data.status === 'success') {
                    this.reminders = this.reminders.filter(r => r.id !== reminderId);
                    if (this.selectedReminder?.id === reminderId) {
                        this.selectedReminder = null;
                    }
                    return true;
                }
                throw new Error(response.data.message || 'Không thể xóa lời nhắc');
            } catch (error) {
                console.error('Error deleting reminder:', error);
                this.error = error.response?.data?.message || 'Không thể xóa lời nhắc';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        setSelectedReminder(reminder) {
            this.selectedReminder = reminder;
        },

        clearSelectedReminder() {
            this.selectedReminder = null;
        },

        clearError() {
            this.error = null;
        }
    }
});
