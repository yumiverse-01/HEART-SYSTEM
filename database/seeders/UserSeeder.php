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
        // create admin
        User::updateOrCreate(
            ['email' => 'admin@sample.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'Administrator',
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
                'role' => 'Worker',
                'status' => 'Active',
            ]
        );
    }
}
