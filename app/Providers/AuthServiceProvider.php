<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        Gate::before(function (User $user, string $ability) {
            // Optional: Super admin bypass — skip all checks
            if ($user->role?->name === 'Super Admin') return true;
        });

        $permissions = [
            // Beneficiaries
            'view-beneficiaries',
            'create-beneficiaries',
            'edit-beneficiaries',
            'delete-beneficiaries',

            // Events
            'view-events',
            'create-events',
            'edit-events',
            'delete-events',

            // Attendance
            'view-attendance',
            'mark-attendance',

            // Service Records
            'view-service-records',
            'create-service-records',
            'edit-service-records',
            'delete-service-records',

            // Reports
            'view-reports',
            'export-reports',

            // Admin
            'access-admin',
            'manage-users',
        ];

        foreach ($permissions as $permission) {
            Gate::define($permission, function (User $user) use ($permission) {
                return in_array($permission, $user->role?->permissions ?? []);
            });
        }
    }
}