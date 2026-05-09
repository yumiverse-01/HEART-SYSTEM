<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'event_id';

    protected $fillable = [
        'event_name',
        'event_type',
        'event_date',
        'location',
        'description',
        'created_by',
        'status',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'event_id', 'event_id');
    }

    public function serviceRecords()
    {
        return $this->hasMany(EventServiceRecord::class, 'event_id', 'event_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }
}
