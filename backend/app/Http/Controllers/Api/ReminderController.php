<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Contracts\ReminderRepositoryInterface;
use App\Http\Request\CreateReminderRequest;
use App\Http\Request\UpdateReminderRequest;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class ReminderController extends Controller
{
    protected $reminderRepository;

    public function __construct(ReminderRepositoryInterface $reminderRepository)
    {
        $this->reminderRepository = $reminderRepository;
    }

    /**
     * Get all reminders for the authenticated user.
     */
    public function index(Request $request)
    {
        try {
            $reminders = $this->reminderRepository->getReminders($request->user()->id);
            return $this->success($reminders);
        } catch (Exception $e) {
            return $this->error('Failed to fetch reminders', $e->getMessage());
        }
    }

    /**
     * Store a newly created reminder in storage.
     */
    public function store(CreateReminderRequest $request)
    {
        try {
            $reminder = $this->reminderRepository->createReminder(
                $request->validated(),
                $request->user()->id
            );
            return $this->success($reminder, 'Reminder created successfully', 201);
        } catch (Exception $e) {
            return $this->error('Failed to create reminder', $e->getMessage());
        }
    }

    /**
     * Update the specified reminder in storage.
     */
    public function update(UpdateReminderRequest $request, Reminder $reminder)
    {
        try {
            if ($reminder->user_id !== $request->user()->id) {
                return $this->unauthorized();
            }
            $updatedReminder = $this->reminderRepository->updateReminder(
                $reminder->id,
                $request->validated()
            );
            return $this->success($updatedReminder, 'Reminder updated successfully');
        } catch (Exception $e) {
            return $this->error('Failed to update reminder', $e->getMessage());
        }
    }

    /**
     * Remove the specified reminder from storage.
     */
    public function destroy(Reminder $reminder, Request $request)
    {
        try {
            if ($reminder->user_id !== $request->user()->id) {
                return $this->unauthorized();
            }
            $this->reminderRepository->deleteReminder($reminder->id);
            return $this->success(null, 'Reminder deleted successfully');
        } catch (Exception $e) {
            return $this->error('Failed to delete reminder', $e->getMessage());
        }
    }
} 