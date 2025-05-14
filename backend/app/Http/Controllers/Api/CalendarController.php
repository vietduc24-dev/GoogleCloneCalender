<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
    /**
     * Store a newly created calendar in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'calendar_name' => 'required|string|max:255',
                'color' => 'nullable|string|max:7',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Log the request data for debugging
            Log::info('Calendar creation request:', [
                'user_id' => $request->user()->id,
                'name' => $request->calendar_name,
                'color' => $request->color,
                'description' => $request->description,
            ]);

            $calendar = Calendar::create([
                'user_id' => $request->user()->id,
                'name' => $request->calendar_name,
                'color' => $request->color ?? '#3490dc',
                'description' => $request->description,
            ]);

            return response()->json($calendar, 201);

        } catch (Exception $e) {
            Log::error('Calendar creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Failed to create calendar',
                'error' => $e->getMessage(),
                'debug_info' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Update the specified calendar in storage.
     */
    public function update(Request $request, Calendar $calendar)
    {
        try {
            // Check if the user owns this calendar
            if ($calendar->user_id !== $request->user()->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $validator = Validator::make($request->all(), [
                'calendar_name' => 'sometimes|required|string|max:255',
                'color' => 'nullable|string|max:7',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $calendar->update([
                'name' => $request->calendar_name ?? $calendar->name,
                'color' => $request->color ?? $calendar->color,
                'description' => $request->description ?? $calendar->description,
            ]);

            return response()->json($calendar);

        } catch (Exception $e) {
            Log::error('Calendar update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Failed to update calendar',
                'error' => $e->getMessage(),
                'debug_info' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Remove the specified calendar from storage.
     */
    public function destroy(Calendar $calendar, Request $request)
    {
        try {
            // Check if the user owns this calendar
            if ($calendar->user_id !== $request->user()->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $calendar->delete();

            return response()->json(['message' => 'Calendar deleted successfully']);

        } catch (Exception $e) {
            Log::error('Calendar deletion failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Failed to delete calendar',
                'error' => $e->getMessage(),
                'debug_info' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }
}
