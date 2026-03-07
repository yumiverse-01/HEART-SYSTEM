<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@sample.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'Active',
            ]
        );
        User::updateOrCreate(
            ['email' => 'healthworker@sample.com'],
            [
                'first_name' => 'Health',
                'last_name' => 'Worker',
                'username' => 'healthworker',
                'password' => Hash::make('hw123'),
                'role' => 'healthworker',
                'status' => 'Active',
            ]
        );
    }
}
