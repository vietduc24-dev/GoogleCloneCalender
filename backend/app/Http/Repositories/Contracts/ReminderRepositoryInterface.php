<?php

namespace App\Http\Repositories\Contracts;

interface ReminderRepositoryInterface
{
    public function getReminders(int $userId);
    public function createReminder(array $data, int $userId);
    public function updateReminder(int $reminderId, array $data);
    public function deleteReminder(int $reminderId);
} 