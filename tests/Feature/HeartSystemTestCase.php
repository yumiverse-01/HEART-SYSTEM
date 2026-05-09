<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

abstract class HeartSystemTestCase extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $worker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndUsers();
    }

    protected function seedRolesAndUsers(): void
    {
        $adminRole = Role::updateOrCreate(
            ['name' => 'Admin'],
            ['description' => 'Administrative access', 'permissions' => ['access-admin', 'manage-users', 'view-reports']]
        );

        $workerRole = Role::updateOrCreate(
            ['name' => 'Worker'],
            ['description' => 'Health worker access', 'permissions' => ['view-beneficiaries', 'mark-attendance', 'create-events', 'create-service-records']]
        );

        $this->admin = User::create([
            'email'      => 'admin@sample.com',
            'first_name' => 'Admin',
            'last_name'  => 'User',
            'username'   => 'adminuser',
            'password'   => Hash::make('admin123'),
            'role_id'    => $adminRole->id,
            'status'     => 'active',
        ]);

        $this->worker = User::create([
            'email'      => 'healthwoker@sample.com',
            'first_name' => 'Health',
            'last_name'  => 'Worker',
            'username'   => 'healthworker',
            'password'   => Hash::make('hw123'),
            'role_id'    => $workerRole->id,
            'status'     => 'active',
        ]);
    }
}
