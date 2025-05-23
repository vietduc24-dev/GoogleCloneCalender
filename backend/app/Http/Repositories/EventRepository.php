<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Contracts\EventRepositoryInterface;
use App\Models\Event;
use App\Models\Calendar;
use Exception;
use Illuminate\Support\Facades\Log;

class EventRepository implements EventRepositoryInterface
{
    public function getEvents(int $calendarId, string $start, string $end)
    {
        try {
            return Event::where('calendar_id', $calendarId)
                ->whereBetween('start_time', [$start, $end])
                ->get()
                ->map(function ($event) {
                    return [
                        'id' => $event->id,
                        'title' => $event->title,
                        'start_date' => $event->start_time,
                        'end_date' => $event->end_time,
                        'all_day' => $event->is_all_day,
                        'description' => $event->description,
                        'location' => $event->location,
                        'color' => $event->color ?? $event->calendar->color
                    ];
                });
        } catch (Exception $e) {
            Log::error('Failed to fetch events:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function createEvent(array $data)
    {
        try {
            $event = Event::create([
                'calendar_id' => $data['calendar_id'],
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'start_time' => $data['start_date'],
                'end_time' => $data['end_date'],
                'location' => $data['location'] ?? null,
                'is_all_day' => $data['all_day'] ?? false,
                'color' => $data['color'] ?? null
            ]);

            return [
                'id' => $event->id,
                'title' => $event->title,
                'start_date' => $event->start_time,
                'end_date' => $event->end_time,
                'all_day' => $event->is_all_day,
                'description' => $event->description,
                'location' => $event->location,
                'color' => $event->color
            ];
        } catch (Exception $e) {
            Log::error('Event creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function updateEvent(int $eventId, array $data)
    {
        try {
            $event = Event::findOrFail($eventId);
            
            $event->update([
                'title' => $data['title'] ?? $event->title,
                'description' => $data['description'] ?? $event->description,
                'start_time' => $data['start_date'] ?? $event->start_time,
                'end_time' => $data['end_date'] ?? $event->end_time,
                'location' => $data['location'] ?? $event->location,
                'is_all_day' => $data['all_day'] ?? $event->is_all_day,
                'color' => $data['color'] ?? $event->color
            ]);

            return [
                'id' => $event->id,
                'title' => $event->title,
                'start_date' => $event->start_time,
                'end_date' => $event->end_time,
                'all_day' => $event->is_all_day,
                'description' => $event->description,
                'location' => $event->location,
                'color' => $event->color
            ];
        } catch (Exception $e) {
            Log::error('Event update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function deleteEvent(int $eventId)
    {
        try {
            $event = Event::findOrFail($eventId);
            $event->delete();
            return true;
        } catch (Exception $e) {
            Log::error('Event deletion failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
} 