<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::updateOrCreate(
            ['name' => 'Super Admin'],
            [
                'description' => 'Full access to all system features and settings.',
                'permissions' => [
                    'view-beneficiaries', 'create-beneficiaries', 'edit-beneficiaries', 'delete-beneficiaries',
                    'view-events', 'create-events', 'edit-events', 'delete-events',
                    'view-attendance', 'mark-attendance',
                    'view-service-records', 'create-service-records', 'edit-service-records', 'delete-service-records',
                    'view-reports', 'export-reports',
                    'access-admin', 'manage-users',
                ],
            ]
        );

        Role::updateOrCreate(
            ['name' => 'Admin'],
            [
                'description' => 'Access to most features with some restrictions on user management.',
                'permissions' => [],
            ]
        );

        Role::updateOrCreate(
            ['name' => 'Worker'],
            [
                'description' => 'Limited access to specific features based on assigned permissions.',
                'permissions' => [
                    'view-beneficiaries', 'create-beneficiaries', 'edit-beneficiaries', 'delete-beneficiaries',
                    'view-events', 'create-events', 'edit-events', 'delete-events',
                    'view-attendance', 'mark-attendance',
                    'view-service-records', 'create-service-records', 'edit-service-records', 'delete-service-records',
                    'view-reports', 'export-reports',
                ],
            ]
        );
    }
}
