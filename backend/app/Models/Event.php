<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'calendar_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'location',
        'is_all_day',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'event_participants');
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function attachments()
    {
        return $this->hasMany(EventAttachment::class);
    }
}