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
        'type',
        'is_all_day',
        'status',
        'notification',
        'color'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_all_day' => 'boolean',
    ];

    protected $attributes = [
        'type' => 'meeting',
        'status' => 'confirmed',
        'notification' => 30,
        'is_all_day' => false,
    ];

    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }
}
