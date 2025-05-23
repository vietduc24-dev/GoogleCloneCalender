<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Contracts\EventRepositoryInterface;
use App\Http\Request\CreateEventRequest;
use App\Http\Request\UpdateEventRequest;
use App\Models\Event;
use App\Models\Calendar;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    protected $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * Get events for a specific calendar and date range.
     */
    public function index(Request $request)
    {
        try {
            $calendar = Calendar::findOrFail($request->calendar_id);
            if ($calendar->user_id !== $request->user()->id) {
                return $this->unauthorized();
            }

            $events = $this->eventRepository->getEvents(
                $request->calendar_id,
                $request->start,
                $request->end
            );

            return $this->success($events);

        } catch (Exception $e) {
            return $this->error('Failed to fetch events', $e->getMessage());
        }
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(CreateEventRequest $request)
    {
        try {
            $calendar = Calendar::findOrFail($request->calendar_id);
            if ($calendar->user_id !== $request->user()->id) {
                return $this->unauthorized();
            }

            $event = $this->eventRepository->createEvent($request->validated());
            return $this->success($event, 'Event created successfully', 201);

        } catch (Exception $e) {
            return $this->error('Failed to create event', $e->getMessage());
        }
    }

    /**
     * Update the specified event in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        try {
            if ($event->calendar->user_id !== $request->user()->id) {
                return $this->unauthorized();
            }
            $updatedEvent = $this->eventRepository->updateEvent($event->id, $request->validated());
            return $this->success($updatedEvent, 'Event updated successfully');

        } catch (Exception $e) {
            return $this->error('Failed to update event', $e->getMessage());
        }
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event, Request $request)
    {
        try {
            if ($event->calendar->user_id !== $request->user()->id) {
                return $this->unauthorized();
            }
            $this->eventRepository->deleteEvent($event->id);
            return $this->success(null, 'Event deleted successfully');

        } catch (Exception $e) {
            return $this->error('Failed to delete event', $e->getMessage());
        }
    }
}
