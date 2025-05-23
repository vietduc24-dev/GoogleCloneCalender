<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Contracts\ReminderRepositoryInterface;
use App\Models\Reminder;
use Exception;
use Illuminate\Support\Facades\Log;

class ReminderRepository implements ReminderRepositoryInterface
{
    public function getReminders(int $userId)
    {
        try {
            return Reminder::where('user_id', $userId)
                ->orderBy('reminder_time')
                ->get()
                ->map(function ($reminder) {
                    return [
                        'id' => $reminder->id,
                        'title' => $reminder->title,
                        'reminder_time' => $reminder->reminder_time,
                        'method' => $reminder->method,
                        'color' => $reminder->color
                    ];
                });
        } catch (Exception $e) {
            Log::error('Failed to fetch reminders:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function createReminder(array $data, int $userId)
    {
        try {
            $reminder = Reminder::create([
                'user_id' => $userId,
                'title' => $data['title'],
                'reminder_time' => $data['reminder_time'],
                'method' => $data['method'] ?? 'notification',
                'color' => $data['color'] ?? '#4285F4'
            ]);

            return [
                'id' => $reminder->id,
                'title' => $reminder->title,
                'reminder_time' => $reminder->reminder_time,
                'method' => $reminder->method,
                'color' => $reminder->color
            ];
        } catch (Exception $e) {
            Log::error('Reminder creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function updateReminder(int $reminderId, array $data)
    {
        try {
            $reminder = Reminder::findOrFail($reminderId);
            
            $reminder->update([
                'title' => $data['title'] ?? $reminder->title,
                'reminder_time' => $data['reminder_time'] ?? $reminder->reminder_time,
                'method' => $data['method'] ?? $reminder->method,
                'color' => $data['color'] ?? $reminder->color
            ]);

            return [
                'id' => $reminder->id,
                'title' => $reminder->title,
                'reminder_time' => $reminder->reminder_time,
                'method' => $reminder->method,
                'color' => $reminder->color
            ];
        } catch (Exception $e) {
            Log::error('Reminder update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function deleteReminder(int $reminderId)
    {
        try {
            $reminder = Reminder::findOrFail($reminderId);
            $reminder->delete();
            return true;
        } catch (Exception $e) {
            Log::error('Reminder deletion failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
} 