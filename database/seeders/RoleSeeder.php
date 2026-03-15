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
            ['name' => 'Administrator'],
            [
                'description' => 'Full access to all system features and settings.',
                'permissions' => json_encode([
                    'create_users' => true,
                    'delete_users' => true,
                    'update_users' => true,
                    'view_users' => true,
                    'create_roles' => true,
                    'delete_roles' => true,
                    'update_roles' => true,
                    'view_roles' => true,
                    'view_reports' => true,
                    'create_beneficiaries' => true,
                    'update_beneficiaries' => true,
                    'delete_beneficiaries' => true,
                    'view_beneficiaries' => true,
                    'create_events' => true,
                    'update_events' => true,
                    'delete_events' => true,
                    'view_events' => true,
                    'create_services' => true,
                    'update_services' => true,
                    'delete_services' => true,
                    'view_services' => true,
                    'mark_attendance' => true,
                    'view_attendance' => true,
                ]),
            ]
        );

        Role::updateOrCreate(
            ['name' => 'Worker'],
            [
                'description' => 'Limited access to specific features based on assigned permissions.',
                'permissions' => json_encode([]),
            ]
        );
    }
}
