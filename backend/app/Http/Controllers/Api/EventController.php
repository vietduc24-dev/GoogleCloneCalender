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
     * Get events for a specific calendar and date range.
     */
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'calendar_id' => 'required|exists:calendars,id',
                'start' => 'required|date',
                'end' => 'required|date|after:start',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Check if user has access to the calendar
            $calendar = Calendar::findOrFail($request->calendar_id);
            if ($calendar->user_id !== $request->user()->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $events = Event::where('calendar_id', $request->calendar_id)
                ->whereBetween('start_time', [$request->start, $request->end])
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

            return response()->json([
                'status' => 'success',
                'data' => $events
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch events:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch events',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'calendar_id' => 'required|exists:calendars,id',
                'title' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'all_day' => 'boolean',
                'description' => 'nullable|string',
                'location' => 'nullable|string|max:255',
                'color' => 'nullable|string|max:7'
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
                'start_time' => $request->start_date,
                'end_time' => $request->end_date,
                'location' => $request->location,
                'is_all_day' => $request->all_day ?? false,
                'color' => $request->color
            ]);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start_date' => $event->start_time,
                    'end_date' => $event->end_time,
                    'all_day' => $event->is_all_day,
                    'description' => $event->description,
                    'location' => $event->location,
                    'color' => $event->color
                ]
            ], 201);

        } catch (Exception $e) {
            Log::error('Event creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create event',
                'error' => $e->getMessage()
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
                'start_date' => 'sometimes|required|date',
                'end_date' => 'sometimes|required|date|after_or_equal:start_date',
                'all_day' => 'boolean',
                'description' => 'nullable|string',
                'location' => 'nullable|string|max:255',
                'color' => 'nullable|string|max:7'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $event->update([
                'title' => $request->title ?? $event->title,
                'description' => $request->description ?? $event->description,
                'start_time' => $request->start_date ?? $event->start_time,
                'end_time' => $request->end_date ?? $event->end_time,
                'location' => $request->location ?? $event->location,
                'is_all_day' => $request->all_day ?? $event->is_all_day,
                'color' => $request->color ?? $event->color
            ]);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start_date' => $event->start_time,
                    'end_date' => $event->end_time,
                    'all_day' => $event->is_all_day,
                    'description' => $event->description,
                    'location' => $event->location,
                    'color' => $event->color
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Event update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update event',
                'error' => $e->getMessage()
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

            return response()->json([
                'status' => 'success',
                'message' => 'Event deleted successfully'
            ]);

        } catch (Exception $e) {
            Log::error('Event deletion failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete event',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
