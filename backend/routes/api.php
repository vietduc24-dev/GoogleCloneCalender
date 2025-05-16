<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\Api\ReminderController;
use Illuminate\Support\Facades\DB;

// Test route to check if API is working
Route::get('/test', function() {
    return response()->json(['message' => 'API is working']);
});

// Public routes
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

        // Calendar routes
        Route::prefix('calendars')->group(function () {
            Route::get('/', [CalendarController::class, 'index']);
            Route::post('/', [CalendarController::class, 'store']);
            Route::put('/{calendar}', [CalendarController::class, 'update']);
            Route::delete('/{calendar}', [CalendarController::class, 'destroy']);
        });

    // Event routes
    Route::prefix('events')->group(function () {
        Route::get('/', [EventController::class, 'index']);
        Route::post('/', [EventController::class, 'store']);
        Route::put('/{event}', [EventController::class, 'update']);
        Route::delete('/{event}', [EventController::class, 'destroy']);
    });

    // Reminder routes
    Route::prefix('reminders')->group(function () {
        Route::get('/', [ReminderController::class, 'index']);
        Route::post('/', [ReminderController::class, 'store']);
        Route::put('/{reminder}', [ReminderController::class, 'update']);
        Route::delete('/{reminder}', [ReminderController::class, 'destroy']);
    });
});

// Fallback for unauthenticated requests
Route::middleware('api')->get('/unauthorized', function () {
    return response()->json(['message' => 'Unauthenticated. Please login first.'], 401);
})->name('login'); 