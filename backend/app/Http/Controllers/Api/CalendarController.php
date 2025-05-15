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
     * Get all calendars for the authenticated user.
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            
            $calendars = Calendar::where('user_id', $user->id)->get();
            
            if ($calendars->isEmpty()) {
                $defaultCalendar = Calendar::create([
                    'user_id' => $user->id,
                    'name' => 'Lịch của tôi',
                    'color' => '#4285F4',
                    'description' => 'Lịch mặc định'
                ]);
                
                return response()->json([
                    'status' => 'success',
                    'data' => [$defaultCalendar]
                ]);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $calendars
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch calendars:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch calendars',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created calendar in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'color' => 'nullable|string|max:7',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $calendar = Calendar::create([
                'user_id' => $request->user()->id,
                'name' => $request->name,
                'color' => $request->color ?? '#4285F4',
                'description' => $request->description,
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $calendar
            ], 201);

        } catch (Exception $e) {
            Log::error('Calendar creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create calendar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified calendar in storage.
     */
    public function update(Request $request, Calendar $calendar)
    {
        try {
            if ($calendar->user_id !== $request->user()->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'color' => 'nullable|string|max:7',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $calendar->update([
                'name' => $request->name ?? $calendar->name,
                'color' => $request->color ?? $calendar->color,
                'description' => $request->description ?? $calendar->description,
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $calendar
            ]);

        } catch (Exception $e) {
            Log::error('Calendar update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update calendar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified calendar from storage.
     */
    public function destroy(Calendar $calendar, Request $request)
    {
        try {
            if ($calendar->user_id !== $request->user()->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $calendar->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Calendar deleted successfully'
            ]);

        } catch (Exception $e) {
            Log::error('Calendar deletion failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete calendar',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
