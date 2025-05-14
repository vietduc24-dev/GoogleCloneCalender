<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'calendar_id' => 'required|exists:calendars,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
                'location' => 'nullable|string|max:255',
                'is_all_day' => 'boolean',
                'recurrence_rule' => 'nullable|string',
                'reminder_minutes' => 'nullable|integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Check if user has access to the calendar
            $calendar = Calendar::findOrFail($request->calendar_id);
            if ($calendar->user_id !== $request->user()->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $event = Event::create([
                'calendar_id' => $request->calendar_id,
                'title' => $request->title,
                'description' => $request->description,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => $request->location,
                'is_all_day' => $request->is_all_day ?? false,
                'recurrence_rule' => $request->recurrence_rule,
                'reminder_minutes' => $request->reminder_minutes,
            ]);

            return response()->json($event, 201);

        } catch (Exception $e) {
            Log::error('Event creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Failed to create event',
                'error' => $e->getMessage(),
                'debug_info' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event)
    {
        try {
            // Check if user has access to the calendar
            if ($event->calendar->user_id !== $request->user()->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|required|date',
                'end_time' => 'sometimes|required|date|after:start_time',
                'location' => 'nullable|string|max:255',
                'is_all_day' => 'boolean',
                'recurrence_rule' => 'nullable|string',
                'reminder_minutes' => 'nullable|integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $event->update([
                'title' => $request->title ?? $event->title,
                'description' => $request->description ?? $event->description,
                'start_time' => $request->start_time ?? $event->start_time,
                'end_time' => $request->end_time ?? $event->end_time,
                'location' => $request->location ?? $event->location,
                'is_all_day' => $request->is_all_day ?? $event->is_all_day,
                'recurrence_rule' => $request->recurrence_rule ?? $event->recurrence_rule,
                'reminder_minutes' => $request->reminder_minutes ?? $event->reminder_minutes,
            ]);

            return response()->json($event);

        } catch (Exception $e) {
            Log::error('Event update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Failed to update event',
                'error' => $e->getMessage(),
                'debug_info' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event, Request $request)
    {
        try {
            // Check if user has access to the calendar
            if ($event->calendar->user_id !== $request->user()->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $event->delete();

            return response()->json(['message' => 'Event deleted successfully']);

        } catch (Exception $e) {
            Log::error('Event deletion failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Failed to delete event',
                'error' => $e->getMessage(),
                'debug_info' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }
}
