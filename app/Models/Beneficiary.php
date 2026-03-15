<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    protected $table = 'beneficiaries';
    protected $primaryKey = 'beneficiary_id';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'birth_date',
        'age',
        'sex',
        'address',
        'contact_number',
        'guardian_name',
        'date_registered',
        'notes',
        'registered_by',
        'role_id',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'beneficiary_id', 'beneficiary_id');
    }

    public function serviceRecords()
    {
        return $this->hasMany(EventServiceRecord::class, 'beneficiary_id', 'beneficiary_id');
    }

    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by', 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
