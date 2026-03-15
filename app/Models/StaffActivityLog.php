<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffActivityLog extends Model
{
    protected $table = 'staff_activity_logs';
    protected $primaryKey = 'activity_id';

    protected $fillable = [
        'user_id',
        'activity_name',
        'activity_type',
        'description',
        'timestamp',
        'activity_details',
        'provided_by',
        'service_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function providedBy()
    {
        return $this->belongsTo(User::class, 'provided_by', 'user_id');
    }
}
