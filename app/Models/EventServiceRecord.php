<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventServiceRecord extends Model
{
    protected $table = 'event_service_records';
    protected $primaryKey = 'service_id';

    protected $fillable = [
        'event_id',
        'beneficiary_id',
        'service_type',
        'diagnosis',
        'treatment_given',
        'remarks',
        'provided_by',
        'service_date',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id', 'beneficiary_id');
    }

    public function providedBy()
    {
        return $this->belongsTo(User::class, 'provided_by', 'user_id');
    }
}
