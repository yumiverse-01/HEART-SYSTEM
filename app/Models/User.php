<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'username',
        'password',
        'role',
        'status',
        'last_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class, 'registered_by', 'user_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'created_by', 'user_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'recorded_by', 'user_id');
    }

    public function serviceRecords()
    {
        return $this->hasMany(EventServiceRecord::class, 'provided_by', 'user_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(StaffActivityLog::class, 'user_id', 'user_id');
    }
}
