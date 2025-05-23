<?php

namespace App\Http\Repositories\Contracts;

interface EventRepositoryInterface
{
    public function getEvents(int $calendarId, string $start, string $end);
    public function createEvent(array $data);
    public function updateEvent(int $eventId, array $data);
    public function deleteEvent(int $eventId);
} 