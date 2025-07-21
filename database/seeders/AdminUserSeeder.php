<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Primary Admin User
        User::updateOrCreate(
            ['email' => 'admin@linchostel.com'],
            [
                'name' => 'LincHostel Admin',
                'password' => bcrypt('admin12345'),
                'role' => 'admin'
            ]
        );

        /*
        //Secondary Admin User
        User::firstOrCreate(
            ['email' => 'admin2@linchostel.com'],
            [
                'name' => 'LincHostel Admin 2',
                'password' => bcrypt('securePassword123'),
                'role' => 'admin'
            ]
        );
        */
    }
}