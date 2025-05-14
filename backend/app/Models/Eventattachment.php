<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'file_url',
        'file_name',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
