<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'reminder_time',
        'method',
        'color',
    ];

    protected $casts = [
        'reminder_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
