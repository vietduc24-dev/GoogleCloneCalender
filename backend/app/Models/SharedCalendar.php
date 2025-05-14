<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharedCalendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'calendar_id',
        'user_id',
        'permission',
    ];

    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
