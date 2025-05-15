<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Log;

class ReminderController extends Controller
{
    /**
     * Get all reminders for the authenticated user.
     */
    public function index(Request $request)
    {
        try {
            $reminders = Reminder::where('user_id', $request->user()->id)
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

            return response()->json([
                'status' => 'success',
                'data' => $reminders
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch reminders:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch reminders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created reminder in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'reminder_time' => 'required|date',
                'method' => 'sometimes|string|in:email,notification,both',
                'color' => 'sometimes|string|max:7'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $reminder = Reminder::create([
                'user_id' => $request->user()->id,
                'title' => $request->title,
                'reminder_time' => $request->reminder_time,
                'method' => $request->method ?? 'notification',
                'color' => $request->color ?? '#4285F4'
            ]);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $reminder->id,
                    'title' => $reminder->title,
                    'reminder_time' => $reminder->reminder_time,
                    'method' => $reminder->method,
                    'color' => $reminder->color
                ]
            ], 201);

        } catch (Exception $e) {
            Log::error('Reminder creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create reminder',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified reminder in storage.
     */
    public function update(Request $request, Reminder $reminder)
    {
        try {
            // Check if user owns the reminder
            if ($reminder->user_id !== $request->user()->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|required|string|max:255',
                'reminder_time' => 'sometimes|required|date',
                'method' => 'sometimes|required|string|in:email,notification,both',
                'color' => 'sometimes|string|max:7'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $reminder->update([
                'title' => $request->title ?? $reminder->title,
                'reminder_time' => $request->reminder_time ?? $reminder->reminder_time,
                'method' => $request->method ?? $reminder->method,
                'color' => $request->color ?? $reminder->color
            ]);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $reminder->id,
                    'title' => $reminder->title,
                    'reminder_time' => $reminder->reminder_time,
                    'method' => $reminder->method,
                    'color' => $reminder->color
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Reminder update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update reminder',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified reminder from storage.
     */
    public function destroy(Reminder $reminder, Request $request)
    {
        try {
            // Check if user owns the reminder
            if ($reminder->user_id !== $request->user()->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $reminder->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Reminder deleted successfully'
            ]);

        } catch (Exception $e) {
            Log::error('Reminder deletion failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete reminder',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 