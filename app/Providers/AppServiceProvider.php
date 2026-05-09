<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Admin-only
        Gate::define('manage-users',         fn($u) => in_array($u->role?->name, ['Super Admin', 'Admin']));
        Gate::define('manage-staff',         fn($u) => in_array($u->role?->name, ['Super Admin', 'Admin']));
        Gate::define('super-admin-only',     fn($u) => $u->role?->name === 'Super Admin');

        // Shared — all authenticated users
        Gate::define('view-beneficiaries',   fn($u) => true);
        Gate::define('view-events',          fn($u) => true);
        Gate::define('view-attendance',      fn($u) => true);
        Gate::define('view-service-records', fn($u) => true);
        Gate::define('view-reports',         fn($u) => true);

        // Write permissions
        Gate::define('edit-beneficiaries',   fn($u) => in_array($u->role?->name, ['Super Admin', 'Admin', 'Worker']));
        Gate::define('delete-beneficiaries', fn($u) => in_array($u->role?->name, ['Super Admin', 'Admin']));
        Gate::define('edit-events',          fn($u) => in_array($u->role?->name, ['Super Admin', 'Admin', 'Worker']));
        Gate::define('delete-events',        fn($u) => in_array($u->role?->name, ['Super Admin', 'Admin']));
        Gate::define('edit-attendance',      fn($u) => in_array($u->role?->name, ['Super Admin', 'Admin', 'Worker']));
        Gate::define('delete-attendance',    fn($u) => in_array($u->role?->name, ['Super Admin', 'Admin']));
        Gate::define('edit-service-records', fn($u) => in_array($u->role?->name, ['Super Admin', 'Admin', 'Worker']));
        Gate::define('delete-service-records',fn($u) => in_array($u->role?->name, ['Super Admin', 'Admin']));
    }
}