<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\CalendarController;
use Illuminate\Support\Facades\DB;



Route::get('/test-connection', function() {
    try {
        DB::connection()->getPdo();
        return response()->json([
            'message' => 'Database connection successful',
            'database' => DB::connection()->getDatabaseName()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Database connection failed',
            'error' => $e->getMessage()
        ], 500);
    }
});

// Public routes
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Calendar routes
    Route::post('/calendars', [CalendarController::class, 'store']);
    Route::put('/calendars/{calendar}', [CalendarController::class, 'update']);
    Route::delete('/calendars/{calendar}', [CalendarController::class, 'destroy']);

    // Event routes
    Route::post('/events', [EventController::class, 'store']);
    Route::put('/events/{event}', [EventController::class, 'update']);
    Route::delete('/events/{event}', [EventController::class, 'destroy']);
});

// Fallback for unauthenticated requests
Route::middleware('api')->get('/unauthorized', function () {
    return response()->json(['message' => 'Unauthenticated. Please login first.'], 401);
})->name('login'); 