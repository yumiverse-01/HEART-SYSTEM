<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create super admin
        User::updateOrCreate(
            ['email' => 'super_admin@sample.com'],
            [
                'first_name' => 'Super Admin',
                'last_name' => 'User',
                'username' => 'super_admin',
                'password' => Hash::make('admin123'),
                'role_id' => 1,
                'status' => 'Active',
            ]
        );

        // create admin
        User::updateOrCreate(
            ['email' => 'admin@sample.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role_id' => 2,
                'status' => 'Active',
            ]
        );

        // healthworker
        User::updateOrCreate(
            ['email' => 'healthworker@sample.com'],
            [
                'first_name' => 'Health',
                'last_name' => 'Worker',
                'username' => 'healthworker',
                'password' => Hash::make('hw123'),
                'role_id' => 3,
                'status' => 'Active',
            ]
        );
    }
}
